<?php

// namespace App\Models;

// // use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;

// class User extends Authenticatable
// {
//     /** @use HasFactory<\Database\Factories\UserFactory> */
//     use HasFactory, Notifiable;

//     /**
//      * The attributes that are mass assignable.
//      *
//      * @var list<string>
//      */
//     protected $fillable = [
//         'name',
//         'email',
//         'password',
//     ];

//     /**
//      * The attributes that should be hidden for serialization.
//      *
//      * @var list<string>
//      */
//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     /**
//      * Get the attributes that should be cast.
//      *
//      * @return array<string, string>
//      */
//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password' => 'hashed',
//         ];
//     }
// }
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * 1. CHAMPS QUE TU PEUX MODIFIER
     */
    protected $fillable = [
        'login',        // REQUIS - Pas unique
        'email',        // REQUIS - Unique
        'password',     // REQUIS
        'phone',        // FACULTATIF - Unique si renseigné
        'address',      // FACULTATIF
        'city',         // FACULTATIF
        'country',      // FACULTATIF
        'avatar',       // FACULTATIF (URL de la photo)
        'role',         // FACULTATIF (Super Admin, Admin, employer, user)
        'is_active',    // FACULTATIF (true/false)
    ];

    /**
     * 2. CHAMPS CACHÉS (ne s'affichent pas dans les réponses API)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 3. CONVERSIONS AUTOMATIQUES
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',  // Date
            'password' => 'hashed',             // Hash automatique
            'is_active' => 'boolean',           // Vrai/Faux
        ];
    }

    // ==================== MÉTHODES UTILES ====================

    /**
     * Vérifie si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'Admin' || $this->role === 'Super Admin';
    }

    /**
     * Vérifie si l'utilisateur est employeur
     */
    public function isEmployer(): bool
    {
        return $this->role === 'employer';
    }

    /**
     * Vérifie si l'utilisateur est actif
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Récupère l'URL de l'avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        // Si l'utilisateur a une photo personnelle
        if ($this->avatar) {
            return $this->avatar;
        }
        
        // Sinon, utilise Gravatar (service d'avatar par email)
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?s=200&d=identicon";
    }

    /**
     * Initiales pour affichage
     */
    public function getInitialsAttribute(): string
    {
        if (empty($this->login)) {
            return substr(strtoupper($this->email), 0, 2);
        }
        
        $names = explode(' ', $this->login);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
            if (strlen($initials) >= 2) break;
        }
        
        return $initials ?: 'US';
    }
}