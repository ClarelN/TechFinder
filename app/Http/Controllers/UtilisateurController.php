<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $utilisateurs= Utilisateur::all();
        return response()->json($utilisateurs,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = request()->validate([
            'code_user' => 'required|string|max:15',
            'nom_user' => 'required|string|max:255',
            'prenom_user' => 'required|string|max:255',
            'login_user' => 'required|string|max:255',
            'password_user' => 'required|string|max:255',
            'tel_user' => 'required|string|max:15',
            'sexe_user' => 'required|in:M,F',
            'role_user' => 'required|in:admin,technicien,client',
            'etat_user' => 'required|in:actif,inactif,suspendu',
        ]);
        
        if (Utilisateur::where('code_user', $validate['code_user'])->exists()) {
            return response()->json(['message' => 'code_user already exists'], 409);
        }

        if (Utilisateur::where('login_user', $validate['login_user'])->exists()) {
            return response()->json(['message' => 'login_user already exists'], 409);
        }

        try {
            $utilisateur = Utilisateur::create($validate);
            return response()->json($utilisateur, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Utilisateur $utilisateur)
    {
        return response()->json($utilisateur, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Utilisateur $utilisateur)
    {
        $validate = request()->validate([
            'nom_user' => 'required|string|max:255',
            'prenom_user' => 'required|string|max:255',
            'login_user' => 'required|string|max:255|unique:utilisateurs,login_user,' . $utilisateur->code_user . ',code_user',
            'password_user' => 'required|string|max:255',
            'tel_user' => 'required|string|max:15',
            'sexe_user' => 'required|in:M,F',
            'role_user' => 'required|in:admin,technicien,client',
            'etat_user' => 'required|in:actif,inactif,suspendu',
        ]);

        try {
            $utilisateur->update($validate);
            return response()->json($utilisateur, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Utilisateur $utilisateur)
    {
        try {
            $utilisateur->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
