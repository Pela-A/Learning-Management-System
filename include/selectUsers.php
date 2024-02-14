<?php
include __DIR__ . '/../model/UsersDB.php';

$userObj = new userDB();

// Fetch options based on the selected category_id
$organization_id = $_POST['organization_id'];


$results = $userObj->get
$sql = "SELECT id, name FROM options WHERE category_id = $category_id";
$result = $conn->query($sql);

$options = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $options[] = $row;
    }
}

echo json_encode($options);

$conn->close();


?>