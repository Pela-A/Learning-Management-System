<?php 

class TrainingEntryDB {
    private $entryData;

    public function __construct() {
        if ($ini = parse_ini_file('dbconfig.ini')) {
            $entryPDO = new PDO("mysql:host=" . $ini['servername'] .
                                ";port=" . $ini['port'] .
                                ";dbname=" . $ini['dbname'],
                                $ini['username'],
                                $ini['password']);
            
            $entryPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $entryPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->entryData = $entryPDO;
        } else {
            throw new Exception("<h2>Creation of database object failed!</h2>", 0, null);
        }
    }

    public function getAllTrainingEntries($orgID) {
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("SELECT * FROM trainingentries INNER JOIN users ON TrainingEntries.userID = users.userID WHERE users.orgID = :o ORDER BY entryDate");
        $sqlString->bindValue(":o", $orgID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getAllUserTrainingEntries($userID) {
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("SELECT * FROM trainingentries INNER JOIN users ON TrainingEntries.userID = users.userID WHERE users.userID = :u ORDER BY entryDate");
        $sqlString->bindValue(":u", $userID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getAllUnvalidatedTrainingEntries($orgID) {
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("SELECT * FROM trainingentries INNER JOIN users ON TrainingEntries.userID = users.userID WHERE isValidated = 0 && users.orgID = :oi ORDER BY entryDate");
        $sqlString->bindValue(":oi", $orgID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function getTrainingEntry($entryID){
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("SELECT * FROM TrainingEntries INNER JOIN users ON TrainingEntries.userID = users.userID WHERE entryID = :id");
        $sqlString->bindValue(":id", $entryID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function createTrainingEntry($userID, $courseName, $entryDate, $completeDate, $creditHours, $category, $description) {
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("INSERT INTO TrainingEntries SET userID = :ui, courseName = :cn, entryDate = :ed, completeDate = :cd, isValidated = :iv, validateDate = :vd, validationComments = :vc, creditHours = :ch, category = :cg, description = :dp");

        $binds = array(
            ":ui" => $userID,
            ":cn" => $courseName,
            ":ed" => $entryDate,
            ":cd" => $completeDate,
            ":iv" => 0,
            ":vd" => null,
            ":vc" => null,
            ":ch" => $creditHours,
            ":cg" => $category,
            ":dp" => $description,
        );

        if($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;

    }

    public function deleteTrainingEntry($entryID){
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("DELETE FROM TrainingEntries WHERE entryID = :id");
        $sqlString->bindValue(":id", $entryID);

        if($sqlString->execute() && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function searchUserTrainingEntry($userID, $courseName, $category) {
        $results = [];
        $entryTable = $this->entryData;
    
        $sqlString = "SELECT * FROM TrainingEntries INNER JOIN users ON TrainingEntries.userID = users.userID WHERE users.userID = :ui";
    
        $binds = [":ui" => $userID];
    
        if ($courseName != '') {
            $sqlString .= " AND courseName LIKE :courseName";
            $binds[':courseName'] = '%' . $courseName . '%';
        }
    
        if ($category != '') {
            $sqlString .= " AND category LIKE :category";
            $binds[':category'] = '%' . $category . '%';
        }
    
        $stmt = $this->entryData->prepare($sqlString);
        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        return $results;
    }
    

    public function searchAllTrainingEntry($orgID, $firstName, $lastName, $courseName, $category) {
        $results = [];
        $entryTable = $this->entryData;
    
        // Base SQL query with table alias and joins
        $sqlString = "SELECT * FROM TrainingEntries AS te INNER JOIN users AS u ON te.userID = u.userID WHERE u.orgID = :oi";
        $binds = [":oi" => $orgID];
    
        // Add conditions based on inputs
        if ($firstName != '') {
            $sqlString .= " AND u.firstName LIKE :first";
            $binds[':first'] = '%'.$firstName.'%';
        }
    
        if ($lastName != '') {
            $sqlString .= " AND u.lastName LIKE :last";
            $binds[':last'] = '%'.$lastName.'%';
        }

        if ($courseName != '') {
            $sqlString .= " AND courseName LIKE :courseName";
            $binds['courseName'] = '%'.$courseName.'%';
        }
    
        if ($category != '') {
            $sqlString .= " AND te.category LIKE :category";
            $binds[':category'] = '%'.$category.'%';
        }
    
        // Prepare the SQL query
        $stmt = $entryTable->prepare($sqlString);
        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        return $results;
    }

    public function updateTrainingEntry($entryID, $userID, $courseName, $entryDate, $completeDate, $isValidated, $validateDate, $validationComments, $creditHours, $category, $description){
        $results = [];
        $entryTable = $this->entryData;

        $sqlString = $entryTable->prepare("UPDATE TrainingEntries SET userID = :ui, courseName = :cn, entryDate = :ed, completeDate = :cd, isValidated = :iv, validateDate = :vd, validationComments = :vc, creditHours = :ch, category = :cg, description = :dp WHERE entryID = :ei");

        $binds = array(
            ":ui" => $userID,
            ":cn" => $courseName,
            ":ed" => $entryDate,
            ":cd" => $completeDate,
            ":iv" => $isValidated,
            ":vd" => $validateDate,
            ":vc" => $validationComments,
            ":ch" => $creditHours,
            ":cg" => $category,
            ":dp" => $description,
            ":ei" => $entryID
        );

        if($sqlString->execute($binds) && $sqlString->rowCount() > 0) {
            $results = $sqlString->fetchAll(PDO::FETCH_ASSOC);
        }

        return $results;
    }

    public function validateTrainingEntry($entryID, $isValidated, $validateDate, $validationComments) {
        $results = [];
        $entryTable = $this->entryData;
    
        // Prepare the SQL statement
        $sqlString = $entryTable->prepare("UPDATE TrainingEntries SET isValidated = :iv, validateDate = :vd, validationComments = :vc WHERE entryID = :id");
        
        // Bind parameters
        $sqlString->bindParam(":iv", $isValidated);
        $sqlString->bindParam(":vd", $validateDate);
        $sqlString->bindParam(":vc", $validationComments);
        $sqlString->bindParam(":id", $entryID);
    
        // Execute the statement
        if($sqlString->execute()) {
            // You don't need to fetch any data as it's an UPDATE query
            // Check if any rows were affected
            if($sqlString->rowCount() > 0) {
                // Rows were affected, update successful
                // Return a success message or any relevant data
                $results['success'] = true;
            } else {
                // No rows were affected, possibly no changes made
                // Return an appropriate message
                $results['success'] = false;
                $results['message'] = "No changes were made.";
            }
        } else {
            // Query execution failed, handle the error
            $results['success'] = false;
            $results['error'] = $sqlString->errorInfo(); // Capture error info for debugging
        }
    
        return $results;
    }
    
}

?>