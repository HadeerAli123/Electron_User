<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function all();
    
    public function find($id);
    
    public function create(array $data);
    
    public function update($id, array $data);
    
    public function delete($id);
    
    public function paginate($perPage = 15);
    
    public function findBy($field, $value);
    
    public function findWhere(array $where);
}