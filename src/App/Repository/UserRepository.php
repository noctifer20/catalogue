<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository {
  public function getById(string $id) {
    return $this->findOneBy(['id' => $id]);
  }
  public function getByEmail(string $email) {
    return $this->findOneBy(['email' => $email]);
  }

}