<?php 

    //

?>

    <aside style="background-color: lightgrey;">
        <h2><?= $_SESSION['firstName']; ?></h2>

        <?php if($_SESSION['isSiteAdmin']): ?>
            <ul>
                <li><a href="organizations.php?action=Viewer">View Organizations</a></li>
                <li><a href="userAccount.php?action=Viewer">View User Accounts</a></li>
                <li><a href="loginAttempts.php?action=Viewer">Login Attempts Manager</a></li>
            </ul>
        <?php endif; ?>

        <?php if($_SESSION['isOrgAdmin']): ?>
            <ul><li><a href="orgControlPanel.php">Organization Control Panel</a></li></ul>
        <?php endif; ?>

        <?php if($_SESSION['isTrainer']): ?>
            <h3>Training Manager</h3>
            <ul>
                <li><a href="trainingValidation.php?action=Validator">User Training Validator</a></li>
                <li><a href="trainingModules.php?action=Create">Create New Training Module</a></li>
                <li><a href="trainingModules.php?action=ViewAll">Training Modules Viewer</a></li>
                <li><a href="trainingEntry.php?action=ViewAll">Training Entry Viewer</a></li>
                <li><a href="loginAttempts.php?action=Viewer">Login Attempts Manager</a></li>
            </ul>
        <?php endif; ?>

        <?php if($_SESSION['isSiteAdmin'] == False): ?>
            <h3>Personal Training</h3>
            <ul>
                <li><a href="trainingEntry.php?action=Create">Log Training Event</a></li>
                <li><a href="trainingModules.php?action=ViewAll">Training Modules Viewer</a></li>
                <li><a href="trainingEntry.php?action=ViewAll">View Past Training</a></li>
                <li><a href="loginAttempts.php?action=Viewer">Login Attempts Manager</a></li>
            </ul>
        <?php endif; ?>

        <a href="userAccount.php?action=personalSettings">Account Settings</a>
        <a href="logout.php">Logout</a>

    </aside>
</body>
</html>