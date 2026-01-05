<?php

// Inclure toutes les entités depuis le dossier Core/Entities
require_once __DIR__ . '/../Core/Entities/BasicUser.php';
require_once __DIR__ . '/../Core/Entities/ProUser.php';
require_once __DIR__ . '/../Core/Entities/Moderator.php';
require_once __DIR__ . '/../Core/Entities/Administrator.php';

class UserFactory
{
    public static function create(string $type, int $id, string $username, string $email)
    {
        switch ($type) {
            case 'basicUser':
                return new BasicUser($id, $username, $email);
            case 'proUser':
                return new ProUser($id, $username, $email);
            case 'moderator':
                return new Moderator($id, $username, $email);
            case 'admin':
                return new Administrator($id, $username, $email);
            default:
                throw new Exception("Type d'utilisateur inconnu : $type");
        }
    }
}
