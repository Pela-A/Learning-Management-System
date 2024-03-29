<?php 

    $pageName = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

    if($pageName == 'landingPage') {
        $pageName = 'Landing Page';
    } elseif($pageName == 'departments') {
        $pageName = 'Department Manager';
    } elseif($pageName == 'loginAttempts') {
        $pageName = 'Login Manager';
    } elseif($pageName == 'organizations') {
        $pageName = 'Organization Manager';
    } elseif($pageName == 'orgControlPanel') {
        $pageName = 'Admin Controller';
    } elseif($pageName == 'trainingEntry') {
        $pageName = 'Training Entries';
    } elseif($pageName == 'trainingModules') {
        $pageName = 'Training Modules';
    } elseif($pageName == 'userAccount') {
        $pageName = 'Account Settings';
    } 

?>

    <aside class="text-light d-flex flex-column justify-content-between">
        <div class="">
        <h2 style="background-color: transparent; box-shadow: 0 0 0 0rem #9e56fd50;"><?= $pageName; ?></h2>

        <?php if($_SESSION['isSiteAdmin']): ?>
            <h4>Site Admin Controller</h4>
            <ul>
                <li>
                    <img src="..\assets\images\atlasPhotos\ModifyOrganization.png" alt="Modify Orgs">
                    <a href="organizations.php?action=Viewer">Manage Organizations</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\ValidateManageNewUsers.png" alt="Modify Users">
                    <a href="userAccount.php?action=Viewer">Manage User Accounts</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\LoginDashboard.png" alt="Login Dashboard">
                    <a href="loginAttempts.php?action=Viewer">Login Dashboard</a>
                </li>
            </ul>
        <?php endif; ?>

        <?php if(($_SESSION['isSiteAdmin'] && isset($_SESSION['orgID'])) || $_SESSION['isOrgAdmin']): ?>
            <h4>Org Admin Controller</h4>
            <ul>
                <li>
                    <img src="..\assets\images\atlasPhotos\ModifyOrganization.png" alt="Modify Organizations">
                    <a href="organizations.php?action=Edit&orgID=<?= $_SESSION['orgID']; ?>">Modify Organization</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\ModifyDepartments.png" alt="Modify Organizations">
                    <a href="departments.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>">Modify Departments</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\ModifyExistingUsers.png" alt="Modify Organizations">
                    <a href="userAccount.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>">Modify User Accounts</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\ValidateManageNewUsers.png" alt="Modify Organizations">
                    <a href="userAccount.php?action=Validator&orgID=<?= $_SESSION['orgID']; ?>">Validate New Users</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\LoginDashboard.png" alt="Modify Organizations">
                    <a href="loginAttempts.php?action=Viewer&orgID=<?= $_SESSION['orgID']; ?>">Login Dashboard</a>
                </li>
            </ul>
        <?php endif; ?>

        <?php if($_SESSION['isTrainer']): ?>
            <h4>Training Manager</h4>
            <ul>
                <li>
                    <img src="..\assets\images\atlasPhotos\ValidateCheckMark.png" alt="Validator">
                    <a href="trainingEntry.php?action=Validator">Training Validator</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\CreateTraining.png" alt="Create Training">
                    <a href="trainingModules.php?action=Create">Create New Training Module</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\ModifyOrganization.png" alt="View training modules">
                    <a href="trainingModules.php?action=ViewAll">Training Modules Viewer</a>
                </li>
                <li>
                    <img src="../assets/images/atlasPhotos/ModifyDepartments.png" alt="View training entries">
                    <a href="trainingEntry.php?action=ViewAll">Training Entry Viewer</a>
                </li>
            </ul>
        <?php endif; ?>

        <?php if($_SESSION['isSiteAdmin'] == false && ($_SESSION['isOrgAdmin']) == false): ?>
            <h4>Personal Training</h4>
            <ul>
                <li>
                    <img src="..\assets\images\atlasPhotos\EnterTraining.png" alt="Enter Training">
                    <a href="trainingEntry.php?action=Create">Log Training Event</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\ViewTraining.png" alt="Training Viewer">
                    <a href="trainingModules.php?action=ViewAll">Training Modules Viewer</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\PastTraining.png" alt="Past Training">
                    <a href="trainingEntry.php?action=ViewAll&userID=<?= $_SESSION['userID']; ?>">View Past Training</a>
                </li>
                <li>
                    <img src="..\assets\images\atlasPhotos\LoginDashboard.png" alt="Login Dashboard">
                    <a href="loginAttempts.php?action=Viewer&userID=<?= $_SESSION['userID']; ?>">Login Dashboard</a>
                </li>
            </ul>

        <?php endif; ?>

        </div>
            
        <div class="row mb-3 aside-options">
            <div class="">
                <img src="..\assets\images\atlasPhotos\Settings.png" alt="Settings">
                <a href="userAccount.php?action=personalSettings">Account Settings</a>
            </div>

            <div class="">
                <img src="..\assets\images\atlasPhotos\LogoutIcon.png" alt="Logout">
                <a href="logout.php">Sign out</a>
            </div>
        </div>
            
    </aside>
</body>
</html>