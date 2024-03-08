<?php
include __DIR__ . '/../model/DepartmentsDB.php';

$depObj = new DepartmentDB();

// Fetch options based on the selected category_id
$organization_id = $_POST['orgID'];
// echo('got to this page');
// echo($organization_id);

$results = $depObj->getAllDepartments($organization_id);

// Encode the results into JSON format and echo it for the response
echo json_encode($results);
?>