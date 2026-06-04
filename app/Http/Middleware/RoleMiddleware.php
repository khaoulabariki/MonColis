<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Gérer une requête entrante et vérifier le rôle de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles Les rôles autorisés à accéder à la route (Ex: admin, livreur)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Récupérer l'utilisateur stocké dans la requête (via la simulation d'Auth ou session)
        // Note : Comme on fait du direct pour demain, on s'attend à ce que l'utilisateur soit passé,
        // ou que son rôle soit vérifié depuis les données envoyées ou la session.
        $utilisateur = $request->attributes->get('utilisateur') ?? auth()->user();

        // 2. Si l'utilisateur n'est pas connecté ou n'existe pas
        if (!$utilisateur) {
            return response()->json([
                'message' => 'Accès refusé. Vous devez être connecté pour accéder à cette ressource.'
            ], 401);
        }

        // 3. Vérifier si le rôle de l'utilisateur fait partie des rôles autorisés
        if (!in_array($utilisateur->role, $roles)) {
            return response()->json([
                'message' => 'Accès interdit. Votre rôle [' . $utilisateur->role . '] ne vous donne pas les droits nécessaires.'
            ], 403);
        }

        return $next($request);
    }
}