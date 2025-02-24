<?php
// Include your database connection
require_once 'db_connection.php';

try {
    return();
    /*
    // Fetch all users from the database
    $sql = "SELECT userID, password FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        $userID = $user['userID'];
        $currentPassword = $user['password'];

        // Check if the password is already hashed
        if (password_needs_rehash($currentPassword, PASSWORD_BCRYPT)) {
            // Hash the old plain text password
            $hashedPassword = password_hash($currentPassword, PASSWORD_BCRYPT);

            // Update the database with the hashed password
            $updateSql = "UPDATE users SET password = :hashedPassword WHERE userID = :userID";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':hashedPassword', $hashedPassword);
            $updateStmt->bindParam(':userID', $userID);
            $updateStmt->execute();

            echo "✅ Updated password for user ID: $userID\n";
        } else {
            echo "⚠️ User ID $userID already has a hashed password.\n";
        }
        
    }


    echo "✅ Password update process completed!";

    */
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>