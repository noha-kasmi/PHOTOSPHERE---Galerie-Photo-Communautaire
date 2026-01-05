<?php

interface RepositoryInterface
{
    public function findById(int $id);  //returner un seule élément de database en id 
    public function findAll(): array;   //retourner tous ls élément du table 
    public function create($entity): bool;  //ajouter un objet dans la database
}
