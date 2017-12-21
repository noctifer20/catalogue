<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ReceiptRepository extends EntityRepository {
  public function getById(string $id) {
    return $this->findOneBy(['id' => $id]);
  }
}
