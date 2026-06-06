<?php

namespace App\Models;

use Core\Model;

class User extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        return $this->findBy('email', $email);
    }

    public function findById($id) {
        return $this->find($id);
    }
}
