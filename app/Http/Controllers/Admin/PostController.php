<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $posts = Post::with('categories', 'user')
            ->when(! $user->isAdmin(), fn ($q) => $q->where('user_id', $user->id))
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->q . '%'))
            ->when($request->filled('kategori'), fn ($q) => $q->inCategory($request->kategori))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest('updated_at')
            ->paginate(15)->withQueryString();

        $categories = Category::ordered()->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        return view('admin.posts.form', [
            'post' => new Post(['status' => 'published', 'published_at' => now()]),
            'categories' => Category::ordered()->get(),
            'selected' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->storeUpload($request->file('thumbnail'), 'posts');
        }

        $post = Post::create($data);
        $post->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.posts.index')->with('success', 'Informasi berhasil ditambahkan.');
    }

    public function edit(Request $request, Post $post)
    {
        $this->authorizePost($request, $post);

        return view('admin.posts.form', [
            'post' => $post,
            'categories' => Category::ordered()->get(),
            'selected' => $post->categories()->pluck('categories.id')->all(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorizePost($request, $post);

        $data = $this->validated($request, $post);

        if ($request->boolean('remove_thumbnail')) {
            $this->deleteUpload($post->thumbnail);
            $data['thumbnail'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->storeUpload($request->file('thumbnail'), 'posts', $post->thumbnail);
        }

        $post->update($data);
        $post->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.posts.index')->with('success', 'Informasi berhasil diperbarui.');
    }

    public function destroy(Request $request, Post $post)
    {
        $this->authorizePost($request, $post);

        $this->deleteUpload($post->thumbnail);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Informasi berhasil dihapus.');
    }

    private function validated(Request $request, ?Post $post = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'alpha_dash', 'max:255', Rule::unique('posts', 'slug')->ignore($post?->id)],
            'content' => ['nullable', 'string'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', Rule::in(array_keys(Post::STATUSES))],
            'published_at' => ['nullable', 'date'],
        ], [
            'categories.required' => 'Pilih minimal satu kategori.',
        ]);

        unset($data['categories'], $data['thumbnail']);

        $data['slug'] = ($data['slug'] ?? null) ?: Post::uniqueSlug($data['title'], $post?->id);

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = $post?->published_at ?? now();
        }

        return $data;
    }

    /** Operator hanya boleh mengelola informasi miliknya sendiri. */
    private function authorizePost(Request $request, Post $post): void
    {
        $user = $request->user();

        abort_unless($user->isAdmin() || $post->user_id === $user->id, 403, 'Anda hanya dapat mengelola informasi milik sendiri.');
    }
}
