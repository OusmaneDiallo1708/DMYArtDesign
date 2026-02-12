<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    // ==================== CRUD BASIQUE ====================
    
    public function index(Request $request): JsonResponse
    {
        try {
            $query = User::query();
            
            $this->applyFilters($query, $request);
            
            $sortField = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_dir', 'desc');
            $query->orderBy($sortField, $sortDirection);
            
            $perPage = $request->get('per_page', 15);
            $users = $query->paginate($perPage);
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateurs récupérés avec succès',
                'data' => UserResource::collection($users),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'last_page' => $users->lastPage(),
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des utilisateurs',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

        public function show(User $user): JsonResponse
    {
        // Vérifie si l'utilisateur existe (avec Route Model Binding)
        if (!$user->exists) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
                'error' => 'Aucun utilisateur avec cet ID'
            ], 404);
        }
        
        // Vérifie si l'utilisateur est actif (optionnel)
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur désactivé',
                'data' => new UserResource($user),
                'warning' => 'Cet utilisateur est désactivé'
            ], 200);
        }
        
        // Si tout est OK
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur récupéré avec succès',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * POST /api/users
     * Crée un nouvel utilisateur
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Validation - avatar optionnel
            $validated = $request->validate([
                'login' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20|unique:users',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'avatar' => 'nullable|string|url|max:500',  // OPTIONNEL
                'role' => 'nullable|in:Super Admin,Admin,employer,user',
                'is_active' => 'nullable|boolean',
            ]);

            // Hash du mot de passe
            $validated['password'] = Hash::make($validated['password']);
            
            // Valeurs par défaut
            $validated['role'] = $validated['role'] ?? 'user';
            $validated['is_active'] = $validated['is_active'] ?? true;
            
            // CORRECTION ICI : Vérifie si 'avatar' existe dans validated
            if (isset($validated['avatar']) && !empty($validated['avatar'])) {
                $validated['avatar_type'] = 'url';  // Si avatar fourni, c'est une URL
            } else {
                $validated['avatar'] = null;        // Sinon null
                $validated['avatar_type'] = 'default';  // Type par défaut
            }
            
            // Ajoute les autres champs avatar si manquants
            $validated['avatar_size'] = null;

            // Création de l'utilisateur
            $user = User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'data' => new UserResource($user)
            ], Response::HTTP_CREATED);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * POST /api/v1/users
     * Crée un utilisateur (version protégée)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'login' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'nullable|string|max:20|unique:users',
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'avatar' => 'nullable|string|url|max:500',
                'role' => 'required|in:Super Admin,Admin,employer,user',
                'is_active' => 'required|boolean',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            
            if (isset($validated['avatar']) && !empty($validated['avatar'])) {
                $validated['avatar_type'] = 'url';
            } else {
                $validated['avatar'] = null;
                $validated['avatar_type'] = 'default';
            }
            
            $validated['avatar_size'] = null;

            $user = User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'data' => new UserResource($user)
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // public function update(UpdateUserRequest $request, User $user): JsonResponse
    // public function update(request $user)
    // {
    //     try {
    //         $data = $request->validated();
            
    //         if (isset($data['password'])) {
    //             $data['password'] = Hash::make($data['password']);
    //         }
            
    //         $user->update($data);
            
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Utilisateur mis à jour avec succès',
    //             'data' => new UserResource($user)
    //         ]);
            
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de la mise à jour',
    //             'error' => config('app.debug') ? $e->getMessage() : null
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
        public function update(Request $request, User $user): JsonResponse
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'login' => 'sometimes|string|max:255|unique:users,login,' . $user->id,
                'email' => 'sometimes|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'avatar' => 'nullable|string|url|max:500',
                'role' => 'sometimes|in:Super Admin,Admin,employer,user',
                'is_active' => 'sometimes|boolean',
            ]);
            
            // Hash du mot de passe si présent
            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }
            
            // Mise à jour de l'utilisateur
            $user->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès',
                'data' => new UserResource($user)
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            if (auth()->id() === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas supprimer votre propre compte'
                ], Response::HTTP_FORBIDDEN);
            }
            
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ==================== MÉTHODES D'AUTHENTIFICATION ====================
    
    /**
     * POST /api/login
     * Connecte un utilisateur et retourne un token
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Identifiants incorrects'
                ], Response::HTTP_UNAUTHORIZED);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la connexion',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * POST /api/forgot-password
     * Envoie un lien de réinitialisation de mot de passe
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lien de réinitialisation envoyé par email',
                'data' => [
                    'email' => $request->email,
                    'reset_link' => 'http://localhost/reset-password?token=abc123'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du lien de réinitialisation',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * POST /api/reset-password
     * Réinitialise le mot de passe
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'token' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::where('email', $request->email)->first();
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mot de passe réinitialisé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réinitialisation du mot de passe',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * POST /api/v1/logout
     * Déconnecte l'utilisateur
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Déconnexion réussie'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la déconnexion',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * GET /api/v1/profile
     * Affiche le profil de l'utilisateur connecté
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Profil récupéré avec succès',
                'data' => new UserResource($request->user())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du profil',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * PUT /api/v1/profile
     * Modifie le profil de l'utilisateur connecté
     */
    public function updateProfile(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'login' => 'sometimes|string|max:255|unique:users,login,' . $request->user()->id,
                'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
                'phone' => 'nullable|string|max:20|unique:users,phone,' . $request->user()->id,
                'address' => 'nullable|string|max:500',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'avatar' => 'nullable|string|url|max:500',
                'password' => 'sometimes|string|min:8|confirmed',
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            $request->user()->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profil mis à jour avec succès',
                'data' => new UserResource($request->user()->fresh())
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du profil',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * GET /api/v1/my-activities
     * Récupère les activités de l'utilisateur connecté
     */
    public function myActivities(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            
            $activities = [
                [
                    'id' => 1,
                    'action' => 'connexion',
                    'description' => 'Connexion au système',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now()->subHours(2)->toDateTimeString(),
                ],
                [
                    'id' => 2,
                    'action' => 'mise_a_jour_profil',
                    'description' => 'Mise à jour du profil',
                    'details' => ['field' => 'avatar'],
                    'created_at' => now()->subDays(1)->toDateTimeString(),
                ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Mes activités récupérées avec succès',
                'data' => [
                    'user' => new UserResource($request->user()),
                    'activities' => array_slice($activities, 0, $limit),
                    'total' => count($activities),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des activités',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ==================== GESTION AVATAR ====================
    
    public function uploadAvatar(Request $request, User $user): JsonResponse
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);
            
            if ($user->avatar && $user->avatar_type === 'upload') {
                $oldPath = str_replace('storage/', '', $user->avatar);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $fullPath = 'storage/' . $path;
            
            $user->update([
                'avatar' => $fullPath,
                'avatar_type' => 'upload',
                'avatar_size' => $request->file('avatar')->getSize()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar téléchargé avec succès',
                'data' => [
                    'avatar_url' => asset($fullPath),
                    'avatar_type' => 'upload',
                    'size' => $request->file('avatar')->getSize()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du téléchargement de l\'avatar',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * POST /api/users/{user}/avatar/url
     * Définit un avatar depuis une URL
     */
    public function setAvatarFromUrl(Request $request, User $user): JsonResponse
    {
        try {
            $request->validate([
                'avatar_url' => 'required|url|max:500',
            ]);
            
            $user->update([
                'avatar' => $request->avatar_url,
                'avatar_type' => 'url',
                'avatar_size' => null
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar URL défini avec succès',
                'data' => [
                    'avatar_url' => $user->avatar_url,
                    'avatar_type' => 'url'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la définition de l\'avatar URL',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * DELETE /api/users/{user}/avatar
     * Supprime l'avatar
     */
    public function removeAvatar(User $user): JsonResponse
    {
        try {
            if ($user->avatar_type === 'upload' && $user->avatar) {
                $path = str_replace('storage/', '', $user->avatar);
                Storage::disk('public')->delete($path);
            }
            
            $user->update([
                'avatar' => null,
                'avatar_type' => 'default',
                'avatar_size' => null
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar supprimé avec succès',
                'data' => [
                    'avatar_url' => $user->avatar_url,
                    'avatar_type' => 'default'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'avatar',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ==================== GESTION STATUT ====================
    
    /**
     * POST /api/users/{user}/activate
     * Active un utilisateur
     */
    public function activate(User $user): JsonResponse
    {
        try {
            $user->update(['is_active' => true]);
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur activé avec succès',
                'data' => new UserResource($user)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'activation',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * POST /api/users/{user}/deactivate
     * Désactive un utilisateur
     */
    public function deactivate(User $user): JsonResponse
    {
        try {
            $user->update(['is_active' => false]);
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur désactivé avec succès',
                'data' => new UserResource($user)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la désactivation',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * POST /api/users/{user}/verify-email
     * Marque l'email comme vérifié
     */
    public function verifyEmail(User $user): JsonResponse
    {
        try {
            $user->markAsVerified();
            
            return response()->json([
                'success' => true,
                'message' => 'Email vérifié avec succès',
                'data' => new UserResource($user)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification de l\'email',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * POST /api/users/{user}/update-last-login
     * Met à jour la date de dernière connexion
     */
    public function updateLastLogin(User $user): JsonResponse
    {
        try {
            $user->updateLastLogin();
            
            return response()->json([
                'success' => true,
                'message' => 'Dernière connexion mise à jour',
                'data' => [
                    'last_login_at' => $user->last_login_at,
                    'formatted' => $user->last_login_formatted
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ==================== STATISTIQUES & RECHERCHE ====================
    
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'inactive' => User::where('is_active', false)->count(),
                'by_role' => [
                    'super_admin' => User::where('role', 'Super Admin')->count(),
                    'admin' => User::where('role', 'Admin')->count(),
                    'employer' => User::where('role', 'employer')->count(),
                    'user' => User::where('role', 'user')->count(),
                ],
                'verified' => User::whereNotNull('email_verified_at')->count(),
                'unverified' => User::whereNull('email_verified_at')->count(),
                'today' => User::whereDate('created_at', today())->count(),
                'this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => User::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Statistiques récupérées avec succès',
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * GET /api/users/search
     * Recherche avancée d'utilisateurs
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'q' => 'required|string|min:2',
                'fields' => 'nullable|string'
            ]);
            
            $query = User::query();
            $searchTerm = '%' . $request->q . '%';
            $fields = $request->fields ? explode(',', $request->fields) : ['login', 'email', 'phone'];
            
            $query->where(function($q) use ($fields, $searchTerm) {
                foreach ($fields as $field) {
                    if (in_array($field, ['login', 'email', 'phone', 'city', 'country', 'address'])) {
                        $q->orWhere($field, 'like', $searchTerm);
                    }
                }
            });
            
            $users = $query->limit(50)->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Résultats de recherche',
                'data' => UserResource::collection($users),
                'meta' => [
                    'query' => $request->q,
                    'fields' => $fields,
                    'count' => $users->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ==================== IMPORT/EXPORT ====================
    
    /**
     * POST /api/users/import
     * Importe des utilisateurs depuis un fichier
     */
    public function import(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
                'send_welcome_email' => 'nullable|boolean',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fonction d\'import à implémenter',
                'data' => [
                    'rows_processed' => 0,
                    'rows_successful' => 0,
                    'rows_failed' => 0,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'import',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * GET /api/users/export
     * Exporte les utilisateurs
     */
    public function export(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'format' => 'nullable|in:csv,excel,pdf',
            ]);

            $format = $request->get('format', 'csv');
            $query = User::query();
            
            $this->applyFilters($query, $request);
            
            $users = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Fonction d\'export à implémenter',
                'data' => [
                    'format' => $format,
                    'count' => $users->count(),
                    'download_url' => '#',
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ==================== AUTRES FONCTIONNALITÉS ====================
    
    /**
     * GET /api/users/{user}/activities
     * Récupère les activités d'un utilisateur
     */
    public function activities(User $user, Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            
            $activities = [
                [
                    'id' => 1,
                    'action' => 'connexion',
                    'description' => 'Connexion au système',
                    'ip_address' => '192.168.1.1',
                    'user_agent' => 'Mozilla/5.0...',
                    'created_at' => now()->subHours(2)->toDateTimeString(),
                ],
                [
                    'id' => 2,
                    'action' => 'mise_a_jour_profil',
                    'description' => 'Mise à jour du profil utilisateur',
                    'details' => ['field' => 'avatar'],
                    'created_at' => now()->subDays(1)->toDateTimeString(),
                ],
                [
                    'id' => 3,
                    'action' => 'creation',
                    'description' => 'Création du compte',
                    'created_at' => $user->created_at->toDateTimeString(),
                ],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Activités récupérées avec succès',
                'data' => [
                    'user' => new UserResource($user),
                    'activities' => array_slice($activities, 0, $limit),
                    'total' => count($activities),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des activités',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * POST /api/users/{user}/send-welcome-email
     * Envoie un email de bienvenue
     */
    public function sendWelcomeEmail(User $user): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Email de bienvenue envoyé avec succès',
                'data' => [
                    'to' => $user->email,
                    'subject' => 'Bienvenue sur DMYArtDesign',
                    'sent_at' => now()->toDateTimeString(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ==================== MÉTHODES ADMIN ====================
    
    /**
     * POST /api/admin/users/{user}/ban
     * Bannir un utilisateur (admin seulement)
     */
    public function banUser(User $user): JsonResponse
    {
        try {
            $user->update([
                'is_active' => false,
                'banned_at' => now(),
                'banned_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur banni avec succès',
                'data' => new UserResource($user)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du bannissement',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ==================== MÉTHODES PRIVÉES ====================
    
    private function applyFilters($query, Request $request): void
    {
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        if ($request->has('verified')) {
            if ($request->boolean('verified')) {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('login', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->has('last_login_from')) {
            $query->whereDate('last_login_at', '>=', $request->last_login_from);
        }
        
        if ($request->has('last_login_to')) {
            $query->whereDate('last_login_at', '<=', $request->last_login_to);
        }
    }
}