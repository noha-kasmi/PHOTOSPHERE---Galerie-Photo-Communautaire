<?php

require_once 'User.php';

class Moderator extends User
{
    public function getRole(): string
    {
        return 'moderateur';
    }

    public function login(string $email, string $password): bool
    {
        if ($this->email === $email && $this->password === $password) {
            return true; 
        }
        return false; 
    }
}
