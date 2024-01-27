<?php 

class TrainingEntryDB {
    private $trainingEntryData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $trainingEntryPDO = new PDO(   "mysql:host=" . $ini['servername'] .
                                        ";port=" . $ini['port'] .
                                        ";dbname=" . $ini['dbname'],
                                        $ini['username'],
                                        $ini['password']);
            
            $trainingEntryPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $trainingEntryPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->trainingEntryData = $trainingEntryPDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getAllTrainingEntrys() {

    }
}

?>