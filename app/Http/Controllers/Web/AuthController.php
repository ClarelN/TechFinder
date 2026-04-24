<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('jwt_token')) {
            return redirect()->route('web.competences.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_user'   => 'required|string',
            'password_user' => 'required|string',
            'role_user'    => 'required|in:admin,technicien,client',
        ], [
            'login_user.required'    => 'Le login est obligatoire.',
            'password_user.required' => 'Le mot de passe est obligatoire.',
            'role_user.required'     => 'Veuillez sélectionner un rôle.',
            'role_user.in'           => 'Rôle invalide.',
        ]);

        $credentials = [
            'login_user' => $request->login_user,
            'password'   => $request->password_user,
            'role_user'  => $request->role_user,
        ];

        try {
            $token = auth('utilisateur')->attempt($credentials);

            if (!$token) {
                return back()
                    ->withInput($request->only('login_user', 'role_user'))
                    ->with('error', 'Identifiants incorrects ou rôle ne correspondant pas.');
            }
        } catch (JWTException) {
            return back()->with('error', 'Impossible de créer la session. Réessayez.');
        }

        session(['jwt_token' => $token]);

        $user = auth('utilisateur')->user();

        return redirect()->route('web.competences.index')
            ->with('success', "Bienvenue, {$user->prenom_user} {$user->nom_user} !");
    }

    public function logout(Request $request)
    {
        $token = session('jwt_token');
        if ($token) {
            try {
                JWTAuth::setToken($token)->invalidate();
            } catch (JWTException) {}
        }
        session()->forget('jwt_token');

        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté.');
    }
}
