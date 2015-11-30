<div class="standard">Please use the links below to begin your experience.</div>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">i</div>
        <div class="inline">My Information</div>
    </div>
    <div>Update your account information</div>
    <ul>
        <li><a href="<?php echo APPURL ?>account.php">My account</a></li>
        <li><a href="<?php echo APPURL ?>account.php?t=4">My skills</a></li>
    </ul>
</div>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">&#9733;</div>
        <div class="inline">Employee Management</div>
    </div>
    <?php if ($_SESSION['user_type']==2) { ?><div>Search for employees to collaborate</div><?php } ?>
    <?php if ($_SESSION['user_type']==1) { ?><div>Add or modify employee accounts</div> <?php } ?>
    <ul>
<?php if ($_SESSION['user_type']==1) { ?>
        <li><a href="<?php echo APPURL ?>employee.php">Employees admin list</a></li>
        <li><a href="<?php echo APPURL ?>employee.php?action=add">Add employee</a></li>
<?php } ?>
        <li><a href="<?php echo APPURL ?>employeesearch.php">Search all employees (for collaboration)</a></li>
    </ul>
</div>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">&#9873;</div>
        <div class="inline">Jobs Management</div>
    </div>
    <?php if ($_SESSION['user_type']==2) { ?><div>Search for jobs that match your skills</div><?php } ?>
    <?php if ($_SESSION['user_type']==1) { ?><div>Add, modify or match jobs with employees</div> <?php } ?>
    <ul>
        <?php 
        if ($_SESSION['user_type']==1) {
            echo '<li><a href="<?php echo APPURL ?>job.php">Jobs admin list</a></li>';
            echo '<li><a href="<?php echo APPURL ?>job.php?action=add">Add job</a></li>';
        }
        ?><li><a href="<?php echo APPURL ?>jobsearch.php">Search available jobs</a></li>
    </ul>
</div>
