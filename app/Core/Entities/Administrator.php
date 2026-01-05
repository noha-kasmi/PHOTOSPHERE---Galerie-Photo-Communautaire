<?php

require_once 'User.php';

class Administrator extends User
{
    public function getRole(): string
    {
        return 'admin';
    }

    public function login(string $email, string $password): bool
    {
        if ($this->email === $email && $this->password === $password) {
            return true; 
        }
        return false; 
    }

    
}
