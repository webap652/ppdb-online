<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        // Setiap kategori yang tampil di menu menjadi satu section di beranda
        $sections = Category::inMenu()->ordered()->get()
            ->map(function (Category $category) {
                $category->setRelation('latestPosts', $category->posts()
                    ->published()->latestPublished()->with('categories')->take(4)->get());

                return $category;
            })
            ->filter(fn (Category $category) => $category->latestPosts->isNotEmpty());

        return view('site.home', compact('sections'));
    }

    /**
     * Semua informasi dari seluruh kategori, dengan filter kategori & pencarian.
     */
    public function posts(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $category = $request->filled('kategori')
            ? Category::where('slug', $request->query('kategori'))->first()
            : null;

        $posts = Post::published()->latestPublished()->with('categories')
            ->when($category, fn ($query) => $query->inCategory($category->slug))
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('title', 'like', "%{$q}%")
                ->orWhere('content', 'like', "%{$q}%")))
            ->paginate(12)->withQueryString();

        return view('site.posts', compact('posts', 'q', 'category'));
    }

    /** URL lama /kategori/{slug} diarahkan ke halaman Informasi dengan filter. */
    public function category(Category $category)
    {
        return redirect()->route('posts.index', ['kategori' => $category->slug], 301);
    }

    public function post(Post $post)
    {
        abort_unless(Post::published()->whereKey($post->id)->exists(), 404);

        $post->increment('views');
        $post->load('categories', 'user');

        $related = Post::published()->latestPublished()->with('categories')
            ->whereKeyNot($post->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $post->categories->pluck('id')))
            ->take(3)->get();

        return view('site.post', compact('post', 'related'));
    }

    public function page(Page $page)
    {
        abort_unless($page->is_published, 404);

        return view('site.page', compact('page'));
    }

    public function contact()
    {
        return view('site.contact');
    }
}
