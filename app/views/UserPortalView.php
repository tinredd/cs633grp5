<div class="standard">Please use the links below to begin your experience.</div>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">i</div>
        <div class="inline">My Information</div>
    </div>
    <div>Update your account information</div>
    <ul>
        <li><a href="/account.php">My account</a></li>
        <li><a href="/account.php?t=4">My skills</a></li>
    </ul>
</div>
<?php if ($_SESSION['user_type']==1) { ?>
<div class="portalpane">
    <div>
        <div class="inline portalheaderimage">&#9733;</div>
        <div class="inline">Employee Management</div>
    </div>
    <div>Add or modify employee accounts</div>
    <ul>
        <li><a href="/employee.php">Employees</a></li>
        <li><a href="/employee.php?action=add">Add employee</a></li>
    </ul>
</div>
<?php } ?>
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
            echo '<li><a href="/job.php">Jobs</a></li>';
            echo '<li><a href="/job.php?action=add">Add job</a></li>';
        }
        ?><li><a href="/jobsearch.php">Search available jobs</a></li>
    </ul>
</div>
