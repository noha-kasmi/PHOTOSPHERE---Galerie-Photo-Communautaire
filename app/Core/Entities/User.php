<?php

abstract class User
{
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected $last_login = null ;


    public function __construct(int $id, string $username, string $email, string $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password ; 
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        $this->password = $password ; 
    }


    abstract public function getRole(): string;

    //abstract public function login(): boolean ;confirmation 
    abstract public function login(): bool ; 

    public function updateLastLogin(): void
    {
        $this->lastLogin = date('Y-m-d H:i:s');
    }

    public function getLastLogin(): ?string
    {
        return $this->lastLogin;
    }
}


?>
