<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CompetenceController;
use App\Http\Controllers\Web\UtilisateurController;

// Page d'accueil → redirige vers login
Route::get('/', fn () => redirect()->route('login'));

// ── Authentification (pas de middleware) ────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Routes protégées par JWT ─────────────────────────────────────────────────
Route::middleware('jwt.verify')->group(function () {

    // Compétences
    Route::get('/Web/competences',                [CompetenceController::class, 'index'])->name('web.competences.index');
    Route::post('/Web/competences',               [CompetenceController::class, 'store'])->name('web.competences.store');
    Route::put('/Web/competences/{code_comp}',    [CompetenceController::class, 'update'])->name('web.competences.update');
    Route::delete('/Web/competences/{code_comp}', [CompetenceController::class, 'destroy'])->name('web.competences.destroy');

    // Utilisateurs
    Route::get('/Web/utilisateurs',                  [UtilisateurController::class, 'index'])->name('web.utilisateurs.index');
    Route::get('/Web/utilisateurs/next-code',         [UtilisateurController::class, 'nextCode'])->name('web.utilisateurs.next-code');
    Route::post('/Web/utilisateurs',                 [UtilisateurController::class, 'store'])->name('web.utilisateurs.store');
    Route::put('/Web/utilisateurs/{code_user}',      [UtilisateurController::class, 'update'])->name('web.utilisateurs.update');
    Route::delete('/Web/utilisateurs/{code_user}',   [UtilisateurController::class, 'destroy'])->name('web.utilisateurs.destroy');
});
