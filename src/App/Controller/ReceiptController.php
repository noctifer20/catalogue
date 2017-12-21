<?php

namespace App\Controller;

use App\Entity\Receipt;
use App\Repository\ReceiptRepository;
use Doctrine\ORM\EntityManager;
use Slim\Http\Request;
use Slim\Http\Response;

class ReceiptController {
  /**
   * @var EntityManager
   */
  private $entityManager;

  /**
   * @var ReceiptRepository
   */
  private $receiptRepository;


  public function __construct(EntityManager $entityManager) {
    $this->entityManager = $entityManager;
    $this->receiptRepository = $this->entityManager->getRepository(Receipt::class);
  }

  public function addReceipt(Request $request, Response $response) {
    $user = $request->getAttribute('user');
    $data = $request->getParsedBody();
    $receipt = new Receipt();
    $receipt->setUser($user['id']);

    foreach ($data as $k => $v) {
      $receipt->{'set' . ucfirst($k)}($v);
    }

    $this->entityManager->persist($receipt);
    $this->entityManager->flush();

    return $response->withJson($receipt->getPublic());
  }

  public function deleteReceipt(Request $request, Response $response, $args) {
    $user = $request->getAttribute('user');
    $id = $args['id'];
    $receipt = $this->receiptRepository->getById($id);
    if($user['id'] !== $receipt->getUser()) {
      return $response->withStatus(403)->withJson(["error" => "Access Denied!"]);
    }
    $this->entityManager->remove($receipt);
    $this->entityManager->flush();

    return $response->withJson(1);
  }

  public function getAllReceipts(Request $request, Response $response) {
    $receipts = $this->receiptRepository->findAll();
    array_walk($receipts, function (&$receipt) {
      return $receipt = $receipt->getPublic();
    });
    return $response->withJson($receipts);
  }

  public function getAllReceiptById(Request $request, Response $response, $args) {
    $id = $args['id'];
    $receipt = $this->receiptRepository->getById($id);

    return $response->withJson($receipt->getPublic());
  }

  public function updateReceiptById(Request $request, Response $response, $args) {
    $user = $request->getAttribute('user');
    $id = $args['id'];
    $data = $request->getParsedBody();
    $receipt = $this->receiptRepository->getById($id);
    if($user['id'] != $receipt->getUser()) {
      return $response->withStatus(403)->withJson(["error" => "Access Denied!"]);
    }

    foreach ($data as $k => $v) {
      $receipt->{'set' . ucfirst($k)}($v);
    }

    $this->entityManager->merge($receipt);
    $this->entityManager->flush();

    return $response->withJson($receipt->getPublic());
  }

}