<?php
include __DIR__ . '/../model/UsersDB.php';

$userObj = new userDB();

// Fetch options based on the selected category_id
$organization_id = $_POST['organization_id'];
echo('got to this page');
echo($organization_id);

$results = $userObj->getAllUsersInOrg($organization_id);
var_dump($results);
for($i=0; $i< count($results); $i++){
    $options[i] = $results[i]['userID']
    echo($i);
}
/*

if (count($results) > 0) {
    while($row = $results) {
        $options[] = $row;
    }
}

json_encode($options);*/

die();

?>