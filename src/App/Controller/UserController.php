<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ImageUploadService;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;
use Slim\Http\Request;
use Slim\Http\Response;

class UserController {
  /**
   * @var EntityManager
   */
  private $entityManager;

  /**
   * @var UserRepository
   */
  private $userRepository;

  /** @var  ImageUploadService */
  private $imageUploadService;

  public function __construct(EntityManager $entityManager, ImageUploadService $imageUploadService) {
    $this->entityManager = $entityManager;
    $this->imageUploadService = $imageUploadService;
    $this->userRepository = $this->entityManager->getRepository(User::class);

  }

  public function signUp(Request $request, Response $response) {
    $data = $request->getParsedBody();
    $user = new User();
    foreach ($data as $k => $v) {
      $user->{'set' . ucfirst($k)}($v);
    }
    $this->entityManager->persist($user);
    $this->entityManager->flush();

    $res = [
      "user" => $user->getPublic(),
      "token" => JWT::encode($user->getPublic(), 'secret')
    ];
    return $response->withJson($res);
  }

  public function signIn(Request $request, Response $response) {
    $data = $request->getParsedBody();
    $user = $this->userRepository->getByEmail($data['email']);

    $res = [
      "user" => $user->getPublic(),
      "token" => JWT::encode($user->getPublic(), 'secret')
    ];
    return $response->withJson($res);
  }

  public function uploadImage(Request $request, Response $response) {
    $uploadedFiles = $request->getUploadedFiles();
    $image = $uploadedFiles['image'];
    $resArr = [];
    if (is_array($image)) {
      foreach ($image as $uploadedFile) {
        $resArr[] = $this->imageUploadService->handleUpload($uploadedFile);
      }
    } else {
      $resArr = $this->imageUploadService->handleUpload($uploadedFiles['image']);
    }

    return $response->withJson($resArr);
  }

  public function isLoggedIn(Request $request, Response $response, $next) {
    if ($request->getAttribute('user')) {
      $response = $next($request, $response);
      return $response;
    } else {
      return $response
        ->withStatus(401)
        ->withJson([
          "error" => "Unauthorized!"
        ]);
    }
  }

  public function fetchUserFromHeader(Request $request, Response $response, $next) {
    if(!empty($request->getHeader('Authorization')) && $decoded = JWT::decode($request->getHeader('Authorization')[0], 'secret', array('HS256'))) {
      $user = $this->userRepository->getById($decoded->id);
      $user = $user->getPublic();
      $request = $request->withAttribute('user', $user);
    }
    return $next($request, $response);
  }
}