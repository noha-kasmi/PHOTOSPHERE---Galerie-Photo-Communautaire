<?php
// creation des object 
require_once '../Entities/BasicUser.php';
require_once '../Entities/ProUser.php';
require_once '../Entities/Moderator.php';
require_once '../Entities/Administrator.php';

class UserFactory
{
    public static function create(string $role, int $id, string $username, string $email): User
    {
        if ($role === 'basicUser') {
            return new BasicUser($id, $username, $email);

        } elseif ($role === 'proUser') {
            return new ProUser($id, $username, $email);

        } elseif ($role === 'moderateur') {
            return new Moderator($id, $username, $email);

        } elseif ($role === 'admin') {
            return new Administrator($id, $username, $email);

        } else {
            throw new Exception('Role inconnu');
        }
    }
}
