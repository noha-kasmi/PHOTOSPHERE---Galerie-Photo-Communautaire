<?php

require_once 'User.php';

class ProUser extends User
{
    public function getRole(): string
    {
        return 'proUser';
    }

    public function login(string $email, string $password): bool
    {
        if ($this->email === $email && $this->password === $password) {
            return true; 
        }
        return false; 
    }
}