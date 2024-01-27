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

    public function getAllDepartments() {

    }
}

?>