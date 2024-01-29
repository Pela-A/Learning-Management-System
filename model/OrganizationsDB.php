<?php 

class OrganizationDB {
    private $organizationData;

    //creating protected variables for class that will be instantiated in the construct
    protected $orgName;
    protected $orgAddress;
    protected $orgCity;
    protected $orgState;
    protected $orgZip;

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

            //planning to make it so that protected variables above are given info from construct function input.
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getAllOrganizations() {

    }

    public function addOrganization() {}
}

?>