<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        Paginator::defaultView('partials.pagination');

        // Identitas situs & data menu untuk semua view website publik
        View::composer(['layouts.site', 'site.*'], function ($view) {
            $view->with($this->app->make('site.shared'));
        });

        $this->app->scoped('site.shared', function () {
            $siteName = Setting::get('site.name', 'SMP Negeri 1 Pirime');
            $logoUrl = Setting::url('site.logo', 'images/logo sekolah.png');

            return [
                'siteName' => $siteName,
                'siteTagline' => Setting::get('site.tagline'),
                'siteDesc' => Setting::get('site.description', $siteName),
                'logoUrl' => $logoUrl,
                'faviconUrl' => Setting::url('site.favicon') ?? $logoUrl,
                'menuPages' => Page::inMenu()->get(['id', 'title', 'slug']),
                'menuCategories' => Category::inMenu()->ordered()->get(['id', 'name', 'slug']),
            ];
        });
    }
}
