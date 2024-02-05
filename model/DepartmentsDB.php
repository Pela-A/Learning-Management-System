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

        return results;
    }
    public function orgAdminGetAllDepartments() {

        $results = [];
        $userTable = $this->userData;

        $sqlString = $userTable->prepare("SELECT userID, orgID, firstName, lastName, letterDate, email, birthDate, phoneNumber, gender, username, password, isOrgAdmin, isSiteAdmin, isTrainer, profilePicture, isVerified");

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return results;
    }
}

?>