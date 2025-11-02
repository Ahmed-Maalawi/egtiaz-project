<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $availableLocales = ['en', 'ar'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale =session('locale') ?? 'en';

        if (in_array($locale, $this->availableLocales)) {
            App::setLocale($locale);
        }

        return $next($request);
    }

    protected function parseAcceptLanguage($header)
    {
        if (!$header) return null;

        foreach (explode(',', $header) as $lang) {
            $code = strtolower(trim(explode(';', $lang)[0]));
            $short = substr($code, 0, 2);
            if (in_array($short, $this->availableLocales)) {
                return $short;
            }
        }

        return null;
    }
}
