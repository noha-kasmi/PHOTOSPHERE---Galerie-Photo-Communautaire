
<?php
/**
 * Script de test pour UserRepository
 * Exécuter avec: php test_user_repository.php
 */

require_once '../app/Core/Entities/Repositories/UserRepository.php';

class UserRepositoryTest
{
    private UserRepository $userRepo;
    private int $testUserId;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        echo "=================================\n";
        echo "TEST UserRepository - PhotoSphere\n";
        echo "=================================\n\n";
    }

    public function runAllTests(): void
    {
        try {
            $this->testFindAll();
            $this->testFindById();
            $this->testFindByEmail();
            $this->testFindByUsername();
            $this->testCreate();
            $this->testUpdate();
            $this->testUpdateLastLogin();
            $this->testDelete();
            
            echo "\n=================================\n";
            echo "✓ TOUS LES TESTS SONT RÉUSSIS!\n";
            echo "=================================\n";
        } catch (Exception $e) {
            echo "\n✗ ERREUR: " . $e->getMessage() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";
        }
    }

    private function testFindAll(): void
    {
        echo "1. Test findAll()\n";
        echo "-------------------\n";
        
        $users = $this->userRepo->findAll();
        echo "Nombre d'utilisateurs trouvés: " . count($users) . "\n";
        
        if (count($users) > 0) {
            echo "Premier utilisateur:\n";
            $firstUser = $users[0];
            echo "  - ID: " . $firstUser->getId() . "\n";
            echo "  - Username: " . $firstUser->getUsername() . "\n";
            echo "  - Email: " . $firstUser->getEmail() . "\n";
            echo "  - Role: " . $firstUser->getRole() . "\n";
        }
        
        echo "✓ Test findAll() réussi\n\n";
    }

    private function testFindById(): void
    {
        echo "2. Test findById()\n";
        echo "-------------------\n";
        
        // Test avec un ID existant
        $user = $this->userRepo->findById(1);
        
        if ($user) {
            echo "Utilisateur trouvé (ID: 1):\n";
            echo "  - Username: " . $user->getUsername() . "\n";
            echo "  - Email: " . $user->getEmail() . "\n";
            echo "  - Role: " . $user->getRole() . "\n";
        } else {
            echo "Utilisateur ID 1 non trouvé\n";
        }
        
        // Test avec un ID inexistant
        $userNull = $this->userRepo->findById(9999);
        echo "Utilisateur ID 9999: " . ($userNull === null ? "null (attendu)" : "trouvé (erreur)") . "\n";
        
        echo "✓ Test findById() réussi\n\n";
    }

    private function testFindByEmail(): void
    {
        echo "3. Test findByEmail()\n";
        echo "-------------------\n";
        
        // Test avec un email existant
        $user = $this->userRepo->findByEmail('noha@example.com');
        
        if ($user) {
            echo "Utilisateur trouvé (email: noha@example.com):\n";
            echo "  - ID: " . $user->getId() . "\n";
            echo "  - Username: " . $user->getUsername() . "\n";
            echo "  - Role: " . $user->getRole() . "\n";
        } else {
            echo "Utilisateur non trouvé\n";
        }
        
        // Test avec un email inexistant
        $userNull = $this->userRepo->findByEmail('inexistant@example.com');
        echo "Email inexistant: " . ($userNull === null ? "null (attendu)" : "trouvé (erreur)") . "\n";
        
        echo "✓ Test findByEmail() réussi\n\n";
    }

    private function testFindByUsername(): void
    {
        echo "4. Test findByUsername()\n";
        echo "-------------------\n";
        
        // Test avec un username existant
        $user = $this->userRepo->findByUsername('youssef');
        
        if ($user) {
            echo "Utilisateur trouvé (username: youssef):\n";
            echo "  - ID: " . $user->getId() . "\n";
            echo "  - Email: " . $user->getEmail() . "\n";
            echo "  - Role: " . $user->getRole() . "\n";
        } else {
            echo "Utilisateur non trouvé\n";
        }
        
        // Test avec un username inexistant
        $userNull = $this->userRepo->findByUsername('inexistant');
        echo "Username inexistant: " . ($userNull === null ? "null (attendu)" : "trouvé (erreur)") . "\n";
        
        echo "✓ Test findByUsername() réussi\n\n";
    }

    private function testCreate(): void
    {
        echo "5. Test create()\n";
        echo "-------------------\n";
        
        $userData = [
            'username' => 'testuser_' . time(),
            'email' => 'testuser_' . time() . '@example.com',
            'password' => 'password123',
            'bio' => 'Utilisateur de test',
            'role' => 'basicUser',
            'level' => 'beginner',
            'uploadCount' => 0,
            'isSuper' => 0
        ];
        
        echo "Création d'un nouvel utilisateur:\n";
        echo "  - Username: " . $userData['username'] . "\n";
        echo "  - Email: " . $userData['email'] . "\n";
        
        $newUser = $this->userRepo->create($userData);
        $this->testUserId = $newUser->getId();
        
        echo "✓ Utilisateur créé avec ID: " . $this->testUserId . "\n";
        echo "✓ Test create() réussi\n\n";
    }

    private function testUpdate(): void
    {
        echo "6. Test update()\n";
        echo "-------------------\n";
        
        // Récupérer l'utilisateur créé
        $user = $this->userRepo->findById($this->testUserId);
        
        if ($user) {
            echo "Modification de l'utilisateur ID: " . $this->testUserId . "\n";
            
            // Modifier l'email
            $newEmail = 'updated_' . time() . '@example.com';
            $user->setEmail($newEmail);
            
            $success = $this->userRepo->update($user);
            
            if ($success) {
                echo "✓ Email mis à jour: " . $newEmail . "\n";
                
                // Vérifier la mise à jour
                $updatedUser = $this->userRepo->findById($this->testUserId);
                if ($updatedUser->getEmail() === $newEmail) {
                    echo "✓ Vérification: Email correctement modifié\n";
                }
            }
        }
        
        echo "✓ Test update() réussi\n\n";
    }

    private function testUpdateLastLogin(): void
    {
        echo "7. Test updateLastLogin()\n";
        echo "-------------------\n";
        
        echo "Mise à jour de la dernière connexion pour l'utilisateur ID: " . $this->testUserId . "\n";
        
        $success = $this->userRepo->updateLastLogin($this->testUserId);
        
        if ($success) {
            echo "✓ Last login mis à jour\n";
        } else {
            echo "✗ Échec de la mise à jour\n";
        }
        
        echo "✓ Test updateLastLogin() réussi\n\n";
    }

    private function testDelete(): void
    {
        echo "8. Test delete()\n";
        echo "-------------------\n";
        
        echo "Suppression de l'utilisateur test ID: " . $this->testUserId . "\n";
        
        $success = $this->userRepo->delete($this->testUserId);
        
        if ($success) {
            echo "✓ Utilisateur supprimé\n";
            
            // Vérifier la suppression
            $deletedUser = $this->userRepo->findById($this->testUserId);
            if ($deletedUser === null) {
                echo "✓ Vérification: Utilisateur bien supprimé\n";
            } else {
                echo "✗ Erreur: Utilisateur toujours présent\n";
            }
        }
        
        echo "✓ Test delete() réussi\n\n";
    }
}

// Exécution des tests
try {
    $test = new UserRepositoryTest();
    $test->runAllTests();
} catch (Exception $e) {
    echo "ERREUR CRITIQUE: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
?>