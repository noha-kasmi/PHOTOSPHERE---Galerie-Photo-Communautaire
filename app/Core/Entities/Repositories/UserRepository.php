<?php

require_once 'RepositoryInterface.php';
require_once '../Core/Database.php';

class UserRepository implements RepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        // Connexion à la DB
        $this->pdo = Database::getConnection();
    }

    // Trouver un utilisateur par ID
    public function findById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE id_user = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne un tableau associatif
    }

    // Retourner tous les utilisateurs
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM utilisateur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Créer un nouvel utilisateur
    public function create($user): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO utilisateur 
            (username, email, password, bio, profile_picture, role, uploadCount, level, isSuper, subscriptionStart, subscriptionEnd) 
            VALUES 
            (:username, :email, :password, :bio, :profile_picture, :role, :uploadCount, :level, :isSuper, :subscriptionStart, :subscriptionEnd)
        ");

        return $stmt->execute([
            ':username' => $user['username'],
            ':email' => $user['email'],
            ':password' => $user['password'], 
            ':bio' => $user['bio'] ?? null,
            ':profile_picture' => $user['profile_picture'] ?? null,
            ':role' => $user['role'] ?? 'basicUser',
            ':uploadCount' => $user['uploadCount'] ?? null,
            ':level' => $user['level'] ?? null,
            ':isSuper' => $user['isSuper'] ?? 0,
            ':subscriptionStart' => $user['subscriptionStart'] ?? null,
            ':subscriptionEnd' => $user['subscriptionEnd'] ?? null
        ]);
    }

    // archive un utilisateur
    

}
