<?php

namespace App\Providers;

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
        // 每次請求只生成一次 Nonce，並分享給所有 .blade 檔
        $nonce = base64_encode(random_bytes(16));
        view()->share('csp_nonce', $nonce);
    }
}
