<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class VerifyJwtToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = session('jwt_token');

        if (!$token) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter.');
        }

        try {
            // On force le guard "utilisateur" (Utilisateur model) pour décoder le token
            $guard = auth('utilisateur');
            $guard->setToken($token);
            $user = $guard->authenticate();

            if (!$user) {
                throw new JWTException('Utilisateur introuvable.');
            }

            // Rend $authUser disponible dans toutes les vues et contrôleurs
            view()->share('authUser', $user);
            $request->attributes->set('authUser', $user);

        } catch (TokenExpiredException) {
            session()->forget('jwt_token');
            return redirect()->route('login')
                ->with('error', 'Session expirée. Veuillez vous reconnecter.');
        } catch (TokenInvalidException | JWTException) {
            session()->forget('jwt_token');
            return redirect()->route('login')
                ->with('error', 'Session invalide. Veuillez vous reconnecter.');
        }

        return $next($request);
    }
}
