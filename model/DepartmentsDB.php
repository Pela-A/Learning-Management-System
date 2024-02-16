<?php 

class DepartmentDB {
    private $departmentData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $departmentPDO = new PDO(   "mysql:host=" . $ini['servername'] .
                                        ";port=" . $ini['port'] .
                                        ";dbname=" . $ini['dbname'],
                                        $ini['username'],
                                        $ini['password']);
            
            $departmentPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $departmentPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->departmentData = $departmentPDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }

        
    }

    public function getDepartment($depID){

        $results = [];
        $departmentTable = $this->departmentData;

        $sqlString = $departmentTable->prepare("SELECT depName, depEmail FROM Departments Where depID = :d");
        $sqlString->bindValue(':d', $depID);

        if($sqlString->execute() && $sqlString->rowCount() > 0){
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    //viewer method
    public function getAllDepartments($orgID) {

        $results = [];
        $departmentTable = $this->departmentData;

        $sqlString = $departmentTable->prepare("SELECT * FROM Departments WHERE orgID = :orgID ORDER BY depName");
        $sqlString->bindValue(':orgID', $orgID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    //create department method
    public function createDep($orgID, $depName, $depEmail){

        $results = "";
        $departmentTable = $this->departmentData;
        $sqlString = $departmentTable->prepare("INSERT INTO departments set orgID = :o, depName = :n, depEmail = :e");

        //bind values
        $binds = array(
            ":o" => $orgID,
            ":n" => $depName,
            ":e" => $depEmail
        );


        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = "New Department Added";
            echo("got here");
        }
        
        return ($results);
    }

    //edit department method
    public function editDep($depID, $depName, $depEmail){

        $results = "";

        $departmentTable = $this->departmentData;
        $sqlString = $departmentTable->prepare("UPDATE departments set depName = :n, depEmail = :e WHERE depID = :d");


        $binds = array(
            ":d" => $depID,
            ":n" => $depName,
            ":e" => $depEmail
        );
        
        
        //if our SQL statement returns results, populate our results confirmation string
        if ($sqlString->execute($binds) && $sqlString->rowCount() > 0){
            $results = 'Data Updated';
        }
        
        return ($results);
    }

    //delete department method
    public function deleteDep($depID){

        $results = "Data was not deleted";

        $departmentTable = $this->departmentData;
        $sqlString = $departmentTable->prepare("DELETE FROM Departments WHERE depID=:id");
        
        
        $sqlString->bindValue(':id', $depID);
            
        if ($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = 'Data Deleted';
        }
        
        return ($results);
    }

    //delete department bridge method
    public function deleteDepBridge($depID){

        $results = "Data was not deleted";

        $departmentTable = $this->departmentData;
        $sqlString = $departmentTable->prepare("DELETE FROM DepUsersBridge WHERE depID=:id");
        
        
        $sqlString->bindValue(':id', $depID);
            
        if ($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = 'Data Deleted';
        }
        
        return ($results);
    }
}

?>