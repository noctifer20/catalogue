<?php
$app->group('/receipt', function () use ($app, $receiptController, $userController) {
//
  $this->get('', [$receiptController, 'getAllReceipts']);
  $this->get('/{id}', [$receiptController, 'getAllReceiptById']);
  $this->patch('/{id}', [$receiptController, 'updateReceiptById'])->add([$userController, 'isLoggedIn']);
  $this->post('', [$receiptController, 'addReceipt'])->add([$userController, 'isLoggedIn']);
  $this->delete('/{id}', [$receiptController, 'deleteReceipt'])->add([$userController, 'isLoggedIn']);
});
