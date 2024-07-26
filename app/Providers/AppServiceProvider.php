<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
        Schema::defaultStringLength(191);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Response::macro('success', function ($data, $message = '', $status = ResponseAlias::HTTP_OK) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        Response::macro('error', function ($message, $status = ResponseAlias::HTTP_BAD_REQUEST) {
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], $status);
        });

        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                ? $rule->mixedCase()->letters()->symbols()->uncompromised()
                : $rule;
        });

        Model::shouldBeStrict(! $this->app->isProduction());

        Builder::macro('whereLike', function ($column, $value) {
            return $this->where($column, 'like', "%{$value}%");
        });
    }
}
