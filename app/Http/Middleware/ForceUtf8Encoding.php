<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceUtf8Encoding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  \
     */
    public function handle(Request \, Closure \): Response
    {
        \ = \(\);
        
        // Forcer l'encodage UTF-8 dans les headers HTTP
        if (\ instanceof \Illuminate\Http\Response) {
            \->header('Content-Type', 'text/html; charset=UTF-8');
        }
        
        return \;
    }
}
