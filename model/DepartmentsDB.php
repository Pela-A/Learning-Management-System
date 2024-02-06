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

    public function siteAdminGetAllDepartments() {
        $results = [];
        $departmentTable = $this->departmentData;

        $sqlString = $departmentTable->prepare("SELECT * FROM departments");

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }
    public function orgAdminGetAllDepartments($orgID) {

        $results = [];
        $departmentTable = $this->departmentData;

        $sqlString = $departmentTable->prepare("SELECT depName, depEmail, WHERE orgID = :o");

        $sqlString->bindValue(':o', $orgID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function createDep($orgID, $depName, $depEmail){

        $results = 0;
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
            $results = (int)$departmentTable->lastInsertId();
        }
        
        return ($results);
    }

    public function editDep($depID, $depName, $depEmail){

        $results = "";

        $departmentTable = $this->departmentData;
        $sqlString = $departmentTable->prepare("UPDATE departments set depName = :n, depEmail = :e WHERE departmentID = :d");


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

    public function deleteDep($depID){

        $results = "Data was not deleted";

        $departmentTable = $this->departmentData;
        $sqlString = $departmentTable->prepare("DELETE FROM Departments WHERE departmentID=:id")
        
        
        $sqlString->bindValue(':id', $depID);
            
        if ($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = 'Data Deleted';
        }
        
        return ($results);
    }

    public function deleteDepBridge($depID){

        $results = "Data was not deleted";

        $departmentTable = $this->departmentData;
        $sqlString = $departmentTable->prepare("DELETE FROM DepUsersBridge WHERE departmentID=:id")
        
        
        $sqlString->bindValue(':id', $depID);
            
        if ($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = 'Data Deleted';
        }
        
        return ($results);
    }
}

?>