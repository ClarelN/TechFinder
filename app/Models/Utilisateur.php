<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Utilisateur extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table      = 'utilisateurs';
    protected $primaryKey = 'code_user';
    public    $incrementing = false;
    protected $keyType    = 'string';
    public    $timestamps = true;

    protected $fillable = [
        'code_user',
        'nom_user',
        'prenom_user',
        'login_user',
        'password_user',
        'tel_user',
        'sexe_user',
        'role_user',
        'etat_user',
    ];

    protected $hidden = ['password_user'];

    // Indique à Laravel quel champ contient le mot de passe hashé
    public function getAuthPassword(): string
    {
        return $this->password_user;
    }

    // --- JWTSubject ---
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'role'  => $this->role_user,
            'login' => $this->login_user,
        ];
    }
    function interventions()
    {
        return $this->hasMany(Intervention::class, 'code_user', 'code_user');
    }


    function competences()
    {

        return $this->belongsToMany(Competence::class, 'user_competences', 'code_user', 'code_comp');
    }

    public function userCompetences()
    {
        return $this->hasMany(User_Competence::class, 'code_user', 'code_user');
    }
}
