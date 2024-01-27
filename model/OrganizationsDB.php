<?php 

class OrganizationDB {
    private $organizationData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $organizationPDO = new PDO(   "mysql:host=" . $ini['servername'] .
                                        ";port=" . $ini['port'] .
                                        ";dbname=" . $ini['dbname'],
                                        $ini['username'],
                                        $ini['password']);
            
            $organizationPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $organizationPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->organizationData = $organizationPDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getAllOrganizations() {

    }
}

?>