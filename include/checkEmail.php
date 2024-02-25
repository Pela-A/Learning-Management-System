<?php
include __DIR__ . '/../model/UsersDB.php';

$userObj = new userDB();

// Get the email from the AJAX request
$email = $_POST['email'];

// Prepare a SQL statement to check if the email exists
$result = $userObj->checkEmail($email);
echo json_encode($result);

?>