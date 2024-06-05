<?php

use Core\App;
use Core\Database;

$db = App::resolve(Database::class);

$currentUserId = 1;

$note = $db->query('select * from notes where id = :id', [
  'id' => $_POST['id']
])->findOrFail();

authorize($note['user_id'] === $currentUserId);

// form was submitted delete the current note
$db->query('delete from notes where id = :id', [
  'id' => $_GET['id']
]);

header('location: /notes');
exit();
