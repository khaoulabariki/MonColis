<?php

use App\Models\Utilisateur;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Cette option définit le guard d'authentification par défaut et le
    | courtier de réinitialisation de mot de passe pour votre application.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'utilisateurs', // Modification ici pour pointer vers 'utilisateurs'
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Ici, vous définissez les guards d'authentification de votre application.
    | Le guard 'web' utilise le stockage de session et le fournisseur 'utilisateurs'.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'utilisateurs', // Modification ici pour lier le guard web au bon fournisseur
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Tous les guards ont un fournisseur d'utilisateurs (User Provider).
    | Nous configurons ici 'utilisateurs' pour utiliser l'ORM Eloquent avec 
    | votre modèle personnalisé : App\Models\Utilisateur.
    |
    */

    'providers' => [
        'utilisateurs' => [ // Changement du nom de la clé en 'utilisateurs'
            'driver' => 'eloquent',
            'model' => Utilisateur::class, // Utilisation directe du modèle personnalisé sans interférence du .env
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Ces options spécifient le comportement de la fonctionnalité de 
    | réinitialisation de mot de passe de Laravel.
    |
    */

    'passwords' => [
        'utilisateurs' => [ // Changement de la clé pour correspondre aux defaults
            'provider' => 'utilisateurs', // Liaison avec le fournisseur personnalisé
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Définition de la durée (en secondes) avant qu'une fenêtre de confirmation
    | de mot de passe n'expire. Par défaut : 3 heures.
    |
    */

    'password_timeout' => 10800,

];