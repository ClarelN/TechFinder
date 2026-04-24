<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    // ── Génère un matricule unique de la forme USR001, USR002… ──────────────
    private function generateCodeUser(): string
    {
        // Point de départ = nombre total d'utilisateurs + 1
        // (le do-while garantit l'unicité même s'il y a des collisions)
        $num = Utilisateur::count() + 1;

        do {
            $code = 'USR' . str_pad($num, 3, '0', STR_PAD_LEFT);
            $num++;
        } while (Utilisateur::where('code_user', $code)->exists());

        return $code;
    }

    // ── Aperçu du prochain code (appelé en AJAX depuis le formulaire) ────────
    public function nextCode()
    {
        return response()->json(['code' => $this->generateCodeUser()]);
    }

    // ── Liste ────────────────────────────────────────────────────────────────
    public function index()
    {
        $utilisateurs_list = Utilisateur::all();
        return view('utilisateur', compact('utilisateurs_list'));
    }

    // ── Création ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom_user'      => 'required|string|max:255',
                'prenom_user'   => 'required|string|max:255',
                'login_user'    => 'required|string|unique:utilisateurs,login_user',
                'password_user' => 'required|string|min:6',
                'tel_user'      => 'nullable|string',
                'sexe_user'     => 'nullable|in:M,F',
                'role_user'     => 'required|in:admin,technicien,client',
                'etat_user'     => 'nullable|in:actif,inactif,suspendu',
            ]);

            // Code généré côté serveur — jamais fourni par le client
            $validated['code_user']     = $this->generateCodeUser();
            $validated['password_user'] = bcrypt($validated['password_user']);
            $validated['etat_user']     = $validated['etat_user'] ?? 'actif';

            $utilisateur = Utilisateur::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Utilisateur « {$utilisateur->prenom_user} {$utilisateur->nom_user} » ajouté (matricule : {$utilisateur->code_user})",
                    'data'    => $utilisateur,
                ], 201);
            }

            return redirect()->route('web.utilisateurs.index')
                ->with('success', "Utilisateur ajouté — matricule : {$utilisateur->code_user}");

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors'  => method_exists($e, 'errors') ? $e->errors() : [],
                ], 422);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    // ── Modification ─────────────────────────────────────────────────────────
    public function update(Request $request, string $id)
    {
        try {
            $utilisateur = Utilisateur::findOrFail($id);

            $validated = $request->validate([
                'nom_user'    => 'required|string|max:255',
                'prenom_user' => 'required|string|max:255',
                'login_user'  => 'required|string|unique:utilisateurs,login_user,' . $id . ',code_user',
                'tel_user'    => 'nullable|string',
                'sexe_user'   => 'nullable|in:M,F',
                'role_user'   => 'required|in:admin,technicien,client',
                'etat_user'   => 'nullable|in:actif,inactif,suspendu',
            ]);

            $utilisateur->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Utilisateur « {$utilisateur->prenom_user} {$utilisateur->nom_user} » modifié avec succès",
                    'data'    => $utilisateur,
                ], 200);
            }

            return redirect()->route('web.utilisateurs.index')
                ->with('success', "Utilisateur modifié avec succès");

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    // ── Suppression ──────────────────────────────────────────────────────────
    public function destroy(Request $request, string $id)
    {
        try {
            $utilisateur = Utilisateur::findOrFail($id);
            $nom = "{$utilisateur->prenom_user} {$utilisateur->nom_user}";
            $utilisateur->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Utilisateur « {$nom} » supprimé avec succès",
                ], 200);
            }

            return redirect()->route('web.utilisateurs.index')
                ->with('success', "Utilisateur « {$nom} » supprimé avec succès");

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
