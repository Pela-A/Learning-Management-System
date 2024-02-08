<?php 

    //

?>

    <aside>
        <h2>Landing Page</h2>

        <?php if($_SESSION['isSiteAdmin']): ?>
            <ul>
                <li><a href="organizations.php">View Organizations</a></li>
                <li><a href="userAccount.php?action=Viewer">View User Accounts</a></li>
            </ul>
        <?php endif; ?>

        <?php if($_SESSION['isOrgAdmin']): ?>
            <ul><li><a href="orgControlPanel.php">Organization Control Panel</a></li></ul>
        <?php endif; ?>

        <?php if($_SESSION['isTrainer']): ?>
            <h3>Training Manager Control Panel</h3>
            <ul>
                <li><a href="trainingValidation.php">User Training Validator</a></li>
                <li><a href="trainingModules.php?action=Create">Create New Training Module</a></li>
                <li><a href="trainingModules.php">Training Modules Viewer</a></li>
                <li><a href="trainingEntry.php">Training Entry Viewer</a></li>
            </ul>
        <?php endif; ?>

        <?php if($_SESSION['isSiteAdmin'] == False): ?>
            <h3>Personal Training</h3>
            <ul>
                <li><a href="trainingEntry.php?action=Create">Log Training Event</a></li>
                <li><a href="trainingModules.php">Training Modules Viewer</a></li>
                <li><a href="userTraining.php">View Past Training</a></li>
            </ul>
        <?php endif; ?>

        <a href="userAccount.php?action=personalSettings">Account Settings</a>
        <a href="logout.php">Logout</a>

    </aside>
</body>
</html>