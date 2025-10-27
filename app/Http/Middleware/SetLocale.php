<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Clase que gestiona las solicitud entrantes
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Accept-Language');
        
        $locale = is_string($header)
            ? substr($header, 0, 2)
            : config('app.locale');
            
        if (in_array($locale, config('app.available_locales', ['es', 'en']))) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}