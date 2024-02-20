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

    <aside class="bg-dark text-light">
        <h2><?= $pageName; ?></h2>

        <?php if($_SESSION['isSiteAdmin']): ?>
            <h4>Site Admin Controller</h4>
            <ul>
                <li><a href="organizations.php?action=Viewer">Manage Organizations</a></li>
                <li><a href="userAccount.php?action=Viewer">Manage User Accounts</a></li>
                <li><a href="loginAttempts.php?action=Viewer">Manage User Login Attempts</a></li>
            </ul>
        <?php endif; ?>

        <?php if($_SESSION['isOrgAdmin']): ?>
            <h4>Org Admin Controller</h4>
            <ul><li><a href="orgControlPanel.php">Organization Control Panel</a></li></ul>
        <?php endif; ?>

        <?php if($_SESSION['isTrainer']): ?>
            <h4>Training Manager</h4>
            <ul>
                <li><a href="trainingEntry.php?action=Validator">User Training Validator</a></li>
                <li><a href="trainingModules.php?action=Create">Create New Training Module</a></li>
                <li><a href="trainingModules.php?action=ViewAll">Training Modules Viewer</a></li>
                <li><a href="trainingEntry.php?action=ViewAll">Training Entry Viewer</a></li>
            </ul>
        <?php endif; ?>

        <h4>Personal Training</h4>
        <ul>
            <li><a href="trainingEntry.php?action=Create">Log Training Event</a></li>
            <li><a href="trainingModules.php?action=ViewAll">Training Modules Viewer</a></li>
            <li><a href="trainingEntry.php?action=ViewAll">View Past Training</a></li>
            <li><a href="loginAttempts.php?action=Viewer">Login Attempts Manager</a></li>
        </ul>


        <a href="userAccount.php?action=personalSettings">Account Settings</a>
        <a href="logout.php">Logout</a>

    </aside>
</body>
</html>