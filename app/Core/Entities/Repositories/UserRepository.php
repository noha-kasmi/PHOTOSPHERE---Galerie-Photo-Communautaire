<?php

require_once '../../Services/UserFactory.php';
require_once '../../Database.php';
require_once '../Factories/UserFactory.php';

class UserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function create(array $data): User
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO utilisateur 
            (username, email, password, bio, profile_picture, role, uploadCount, level, isSuper, subscriptionStart, subscriptionEnd) 
            VALUES 
            (:username, :email, :password, :bio, :profile_picture, :role, :uploadCount, :level, :isSuper, :subscriptionStart, :subscriptionEnd)
        ");

        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':bio' => $data['bio'] ?? null,
            ':profile_picture' => $data['profile_picture'] ?? null,
            ':role' => $data['role'] ?? 'basicUser',
            ':uploadCount' => $data['uploadCount'] ?? 0,
            ':level' => $data['level'] ?? 'beginner',
            ':isSuper' => $data['isSuper'] ?? 0,
            ':subscriptionStart' => $data['subscriptionStart'] ?? null,
            ':subscriptionEnd' => $data['subscriptionEnd'] ?? null
        ]);

        $userId = (int)$this->pdo->lastInsertId();
        return $this->findById($userId);
    }

    /**
     * Trouver un utilisateur par ID
     */
    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE id_user = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$userData) {
            return null;
        }

        return $this->mapToUserObject($userData);
    }

    /**
     * Trouver un utilisateur par email
     */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$userData) {
            return null;
        }

        return $this->mapToUserObject($userData);
    }

    /**
     * Trouver un utilisateur par username
     */
    public function findByUsername(string $username): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur WHERE username = :username");
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$userData) {
            return null;
        }

        return $this->mapToUserObject($userData);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(User $user): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE utilisateur 
            SET username = :username, 
                email = :email, 
                password = :password,
                bio = :bio,
                profile_picture = :profile_picture,
                role = :role,
                uploadCount = :uploadCount,
                level = :level,
                isSuper = :isSuper,
                subscriptionStart = :subscriptionStart,
                subscriptionEnd = :subscriptionEnd
            WHERE id_user = :id
        ");

        return $stmt->execute([
            ':id' => $user->getId(),
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword(),
            ':bio' => $user->getBio(),
            ':profile_picture' => $user->getProfilePicture(),
            ':role' => $user->getRole(),
            ':uploadCount' => $user->getUploadCount(),
            ':level' => $user->getLevel(),
            ':isSuper' => $user->getIsSuper(),
            ':subscriptionStart' => $user->getSubscriptionStart(),
            ':subscriptionEnd' => $user->getSubscriptionEnd()
        ]);
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM utilisateur WHERE id_user = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Retourner tous les utilisateurs avec pagination
     */
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateur LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $users = [];
        while ($userData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->mapToUserObject($userData);
        }
        
        return $users;
    }

    /**
     * Mettre à jour la date de dernière connexion
     */
    public function updateLastLogin(int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE utilisateur 
            SET last_login = CURRENT_TIMESTAMP 
            WHERE id_user = :id
        ");
        
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Mapper les données de la base vers un objet User en utilisant UserFactory
     */
    private function mapToUserObject(array $userData): User
    {
        $user = UserFactory::create(
            $userData['role'],
            $userData['id_user'],
            $userData['username'],
            $userData['email']
        );
        
        // Définir le mot de passe (déjà hashé depuis la base)
        $user->setPassword($userData['password']);
        
        // Définir les autres propriétés si vos classes ont ces setters
        if (method_exists($user, 'setBio')) {
            $user->setBio($userData['bio']);
        }
        if (method_exists($user, 'setProfilePicture')) {
            $user->setProfilePicture($userData['profile_picture']);
        }
        if (method_exists($user, 'setUploadCount')) {
            $user->setUploadCount($userData['uploadCount']);
        }
        if (method_exists($user, 'setLevel')) {
            $user->setLevel($userData['level']);
        }
        if (method_exists($user, 'setIsSuper')) {
            $user->setIsSuper($userData['isSuper']);
        }
        if (method_exists($user, 'setSubscriptionStart')) {
            $user->setSubscriptionStart($userData['subscriptionStart']);
        }
        if (method_exists($user, 'setSubscriptionEnd')) {
            $user->setSubscriptionEnd($userData['subscriptionEnd']);
        }
        
        return $user;
    }
}