<?php
$userController = $app->getContainer()->get(\App\Controller\UserController::class);
$receiptController = $app->getContainer()->get(\App\Controller\ReceiptController::class);


$app->group('/api', function () use ($app, $userController, $receiptController) {
  $app->group('/v1', function () use ($app, $userController, $receiptController) {
    include_once 'user.route.php';
    include_once 'receipt.route.php';
  });
})->add([$userController, 'fetchUserFromHeader']);