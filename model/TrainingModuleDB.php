<?php 

class TrainingModuleDB {
    private $trainingModuleData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $trainingModulePDO = new PDO(   "mysql:host=" . $ini['servername'] .
                                        ";port=" . $ini['port'] .
                                        ";dbname=" . $ini['dbname'],
                                        $ini['username'],
                                        $ini['password']);
            
            $trainingModulePDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $trainingModulePDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->trainingModuleData = $trainingModulePDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getAllTrainingModules() {

    }

    public function getTrainingModule(){

    }

    public function createTrainingModule(){

    }

    public function deleteTrainingModule(){

    }

    public function searchTrainingModule(){

    }

    public function updateTrainingModule(){

    }

}

?>