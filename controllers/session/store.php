<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$email = $_POST['email'];
$password = $_POST['password'];

// validate form inputs
$errors = [];

if (!Validator::email($email)) {
  $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($password)) {
  $errors['password'] = 'Please provide a valid password.';
}

if (!empty($errors)) {
  return view('session/create.view.php', [
    'errors' => $errors
  ]);
}

// match the credentials
$user = $db->query('select * from users where email = :email', [
  'email' => $email
])->find();

// we have a user, but we don't know if the password provided matches what we have in the database
if ($user) {
  if (password_verify($password, $user['password'])) {
    login([
      'email' => $email
    ]);

    header('location: /');
    exit();
  }
}

return view('session/create.view.php', [
  'errors' => [
    'email' => 'No matching account found for that email address and password'
  ]
]);
