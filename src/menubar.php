<?php
global $lang;
$userLvl = getUserAccountLevel(getCurrentUserId());
?>
<div class="menubar">
    <ul>
        <h1><?= $lang["manage"] ?></h1>
        <?php if ($userLvl === 0) { ?>
            <li><a href="index.php"><?= $lang["upgrade"] ?></a></li>
        <?php } elseif ($userLvl === 1) { ?>
            <li><a href="index.php"><?= $lang["myvideos"] ?></a></li>
            <li><a href="index.php"><?= $lang["uploadvideos"] ?></a></li>
        <?php } elseif ($userLvl === 2) { ?>
            <li><a href="index.php"><?= $lang["users"] ?></a></li>
            <li><a href="index.php"><?= $lang["allvideos"] ?></a></li>
            <li><a href="index.php"><?= $lang["allstudios"] ?></a></li>
        <?php } ?>
        <li><a href="changePassword.php"><?= $lang["changepassword"] ?></a></li>
        <li><a href="logout.php"><?= $lang["logout"] ?></a></li>
    </ul>
</div>