<?php

require_once 'User.php';

class BasicUser extends User
{
    protected int $uploadCount = 0;

    public function getUploadCount(): int {
        return $this-> uploadCount ;
    }

    public function getRole(): string
    {
        return 'basicUser';
    }

    public function login(string $email, string $password): bool
    {
        if ($this->email === $email && $this->password === $password) {
            return true; 
        }
        return false; 
    }

    public function incUploadCount() {
        return $this->uploadCount++ ;
    }

    public function UploadPhoto() {
        return $this->uploadCount < 10 ;
    }

    public function creerAlbumPrive() : bool {
        return false ;
    }

    // public function UploadPhoto() {
    //     return $this->UploadCount = 0 ; 
    // }
}