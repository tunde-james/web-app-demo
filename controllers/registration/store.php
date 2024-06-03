<?php

use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];

// validate form inputs
$errors = [];

if (!Validator::email($email)) {
  $errors['email'] = 'Please provide a valid email address.';
}

if (!Validator::string($password, 7, 255)) {
  $errors['password'] = 'Please provide a password of at least 7 characters.';
}

if (!empty($errors)) {
  return view('registration/create.view.php', [
    'errors' => $errors
  ]);
}

// check if the account already exists
$db = App::resolve(Database::class);

$user = $db->query('select * from users where email = :email', [
  'email' => $email
])->find();

// if yes, redirect to a login page
if ($user) {
  header('location: /');
  exit();
} else {
  // if not, save one to the database and then log the user in, and then redirect
  $db->query('INSERT INTO users(email, password) VALUES(:email, :password)', [
    'email' => $email,
    'password' => $password
  ]);

  // mark that the user has logged in
  $_SESSION['user'] = [
    'email' => $email
  ];

  header('location: /');
  exit();
}
