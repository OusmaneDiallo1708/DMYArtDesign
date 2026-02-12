<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

// ==================== SECTION 1 : ROUTES PUBLIQUES (SANS TOKEN) ====================
// 🔓 ACCÈS : Public - Pas besoin d'authentification
// 📍 URL : /api/...

// Route de santé - Vérifie si l'API fonctionne
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'service' => 'DMYArtDesign API',
        'version' => '1.0.0',
        'timestamp' => now()->toDateTimeString(),
    ]);
});

// ==================== SECTION 2 : ROUTES D'AUTHENTIFICATION (SANS TOKEN) ====================
// 🔓 ACCÈS : Public - Pour obtenir un token
// 📍 URL : /api/...

// Route d'inscription (créer un compte)
Route::post('/register', [UserController::class, 'register']);

// Route de connexion (obtenir un token)
Route::post('/login', [UserController::class, 'login']);

// Route pour afficher un utilisateur (publique - méthode alternative)
Route::get('/index', [UserController::class, 'index']);
// Route pour afficher un utilisateur (publique - méthode alternative)
Route::get('/show/{user}', [UserController::class, 'show']);
// Route pour Modifier un utilisateur
Route::put('/update/{user}', [UserController::class, 'update']);
// Route pour supprimer un utilisateur
Route::delete('/destroy/{user}', [UserController::class, 'destroy']);

// Route de mot de passe oublié
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);

// Route de réinitialisation de mot de passe
Route::post('/reset-password', [UserController::class, 'resetPassword']);

// ==================== SECTION 3 : ROUTES API V1 (TOKEN REQUIS) ====================
// 🔐 ACCÈS : Privé - Token OBLIGATOIRE
// 📍 URL : /api/v1/...
// ⚠️  Toutes ces routes nécessitent : Header "Authorization: Bearer VOTRE_TOKEN"

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // ============ 3.1 CRUD COMPLET DES UTILISATEURS ============
    // 🔐 Token REQUIS pour toutes ces routes
    
    // GET    /api/v1/users          → Liste tous les utilisateurs
    // POST   /api/v1/users          → Crée un nouvel utilisateur
    // GET    /api/v1/users/{id}     → Affiche un utilisateur
    // PUT    /api/v1/users/{id}     → Met à jour un utilisateur
    // DELETE /api/v1/users/{id}     → Supprime un utilisateur
    Route::apiResource('users', UserController::class);
    
    // ============ 3.2 GESTION DES AVATARS ============
    // 🔐 Token REQUIS + permission sur l'utilisateur {id}
    
    // POST   /api/v1/users/{id}/avatar      → Upload un avatar (fichier)
    // POST   /api/v1/users/{id}/avatar/url  → Définit avatar par URL
    // DELETE /api/v1/users/{id}/avatar      → Supprime l'avatar
    Route::prefix('users/{user}')->group(function () {
        Route::post('/avatar', [UserController::class, 'uploadAvatar']);
        Route::post('/avatar/url', [UserController::class, 'setAvatarFromUrl']);
        Route::delete('/avatar', [UserController::class, 'removeAvatar']);
    });
    
    // ============ 3.3 GESTION DU STATUT DES COMPTES ============
    // 🔐 Token REQUIS + permissions administratives
    
    // POST /api/v1/users/{id}/activate         → Active un compte
    // POST /api/v1/users/{id}/deactivate       → Désactive un compte
    // POST /api/v1/users/{id}/verify-email     → Vérifie l'email
    // POST /api/v1/users/{id}/update-last-login → Met à jour dernière connexion
    // GET  /api/v1/users/{id}/activities       → Liste les activités
    // POST /api/v1/users/{id}/send-welcome-email → Envoie email de bienvenue
    Route::prefix('users/{user}')->group(function () {
        Route::post('/activate', [UserController::class, 'activate']);
        Route::post('/deactivate', [UserController::class, 'deactivate']);
        Route::post('/verify-email', [UserController::class, 'verifyEmail']);
        Route::post('/update-last-login', [UserController::class, 'updateLastLogin']);
        Route::get('/activities', [UserController::class, 'activities']);
        Route::post('/send-welcome-email', [UserController::class, 'sendWelcomeEmail']);
    });
    
    // ============ 3.4 STATISTIQUES ET OPERATIONS ============
    // 🔐 Token REQUIS + permissions administratives
    
    // GET  /api/v1/users/stats    → Statistiques (admin)
    // GET  /api/v1/users/search   → Recherche avancée
    // POST /api/v1/users/import   → Import massif (admin)
    // GET  /api/v1/users/export   → Export des données (admin)
    Route::get('/users/stats', [UserController::class, 'stats']);
    Route::get('/users/search', [UserController::class, 'search']);
    Route::post('/users/import', [UserController::class, 'import']);
    Route::get('/users/export', [UserController::class, 'export']);
    
    // ============ 3.5 PROFIL UTILISATEUR CONNECTÉ ============
    // 🔐 Token REQUIS - Pour l'utilisateur connecté
    
    // GET  /api/v1/profile        → Voir son propre profil
    // PUT  /api/v1/profile        → Modifier son profil
    // GET  /api/v1/my-activities  → Mes activités
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::get('/my-activities', [UserController::class, 'myActivities']);
    
    // ============ 3.6 DÉCONNEXION ============
    // 🔐 Token REQUIS - Pour invalider le token
    
    // POST /api/v1/logout → Se déconnecter (invalide le token)
    Route::post('/logout', [UserController::class, 'logout']);
});

// ==================== SECTION 4 : ROUTES ADMIN (TOKEN ADMIN REQUIS) ====================
// 🔐🔐 ACCÈS : Super privé - Token ADMIN obligatoire
// 📍 URL : /api/admin/...
// ⚠️  Middleware supplémentaire pour vérifier les rôles

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    
    // GET    /api/admin/users           → Liste users (vue admin)
    // DELETE /api/admin/users/{id}      → Suppression forcée
    // POST   /api/admin/users/{id}/ban  → Bannir un utilisateur
    Route::apiResource('users', UserController::class)->only(['index', 'destroy']);
    Route::post('/users/{user}/ban', [UserController::class, 'banUser']);
});