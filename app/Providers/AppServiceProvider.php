<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


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
        DB::listen(function ($q) {
            if (Str::contains($q->sql, 'order by `id`')) {
                \Log::channel('single')->debug('ORDER_BY_ID', [
                    'sql' => $q->sql,
                    'bindings' => $q->bindings,
                    'trace' => collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))
                                ->take(10)->pluck('file')->filter()->values(),
                ]);
            }
        });
    }

    protected $policies = [
        \App\Models\Product::class => \App\Policies\ProductPolicy::class,
    ];
}
