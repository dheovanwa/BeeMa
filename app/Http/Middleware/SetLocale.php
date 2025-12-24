<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Get locale from session, default to 'en'
        $locale = session('locale', 'en');

        // Validate locale is one of the supported languages
        if (in_array($locale, ['en', 'id'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
