<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $scope = fn () => Post::query()->when(! $user->isAdmin(), fn ($q) => $q->where('user_id', $user->id));

        $stats = [
            ['label' => 'Total Informasi', 'value' => $scope()->count(), 'icon' => 'fa-newspaper'],
            ['label' => 'Terbit', 'value' => $scope()->where('status', 'published')->count(), 'icon' => 'fa-circle-check'],
            ['label' => 'Draf', 'value' => $scope()->where('status', 'draft')->count(), 'icon' => 'fa-file-pen'],
            ['label' => 'Total Dilihat', 'value' => (int) $scope()->sum('views'), 'icon' => 'fa-eye'],
        ];

        if ($user->isAdmin()) {
            $stats[] = ['label' => 'Halaman', 'value' => Page::count(), 'icon' => 'fa-file-lines'];
            $stats[] = ['label' => 'Akun', 'value' => User::count(), 'icon' => 'fa-users'];
        }

        $categories = Category::ordered()
            ->withCount(['posts' => fn ($q) => $q->when(! $user->isAdmin(), fn ($w) => $w->where('user_id', $user->id))])
            ->get();

        $latestPosts = $scope()->with('categories', 'user')->latest('updated_at')->take(8)->get();

        return view('admin.dashboard', compact('stats', 'categories', 'latestPosts'));
    }
}
