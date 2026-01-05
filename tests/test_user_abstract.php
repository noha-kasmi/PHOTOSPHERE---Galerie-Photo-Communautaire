<?php

require_once __DIR__ . '/../app/Core/Entities/User.php';

class BasicUser extends User {
    public function getRole(): string {
        return 'basic';
    }
}

$user = new BasicUser(1, 'test', 'test@test.com');
echo $user->getRole(); // basic

