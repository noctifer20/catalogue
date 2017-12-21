<?php
$app->group('/user', function () use ($app, $userController) {

  $this->post('/signup', [$userController, 'signUp']);
  $this->post('/signin', [$userController, 'signIn']);
  $this->post('/image', [$userController, 'uploadImage'])->add([$userController, 'isLoggedIn']);

});
