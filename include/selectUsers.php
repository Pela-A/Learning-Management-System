<?php
include __DIR__ . '/../model/UsersDB.php';

$userObj = new userDB();

// Fetch options based on the selected category_id
$organization_id = $_POST['organization_id'];
// echo('got to this page');
// echo($organization_id);

$results = $userObj->getAllUsersInOrg($organization_id);

// Encode the results into JSON format and echo it for the response
echo json_encode($results);
?>