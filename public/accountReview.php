<?php
require_once "../src/config.php";
require_once "../src/accountMod.php";
require_once "../src/accountReview.php";
require_once "../src/genres.php";
global $lang;

showHead($lang["accountModeration"], ['assets/css/accountReview.css']);
?>
    <body>
    <?php showHeader(); ?>

    <div id="container">
        <div id="accountInfo">
            <div class="studioInfo">
                <p class="label"><?= $lang["accountId"] ?></p>
                <p><?= $companyInfo["id"] ?></p>
            </div>
            <div class="studioInfo">
                <p class="label"><?= $lang["studioName"] ?></p>
                <p><?= $companyInfo["studioName"] ?></p>
            </div>
            <div class="studioInfo">
                <p class="label"><?= $lang["genre"] ?></p>
                <p><?= idToGenre($id, $companyInfo) ?></p>
            </div>
            <div class="studioInfo">
                <p class="label"><?= $lang["iban"] ?></p>
                <p><?= $companyInfo["iban"] ?></p>
            </div>
            <div class="studioInfo">
                <p class="label"><?= $lang["address"] ?></p>
                <p><?= $companyInfo["address"] ?></p>
            </div>
            <div class="studioInfo">
                <p class="label"><?= $lang["city"] ?></p>
                <p><?= $companyInfo["city"] ?></p>
            </div>
        </div>

        <div>
            <form action="<?= htmlentities("accountReview.php?userId=$id") ?>" method="post">
                <input type="submit" name="approve" value="<?= $lang["approve"] ?>">
            </form>
        </div>
    </div>
    </body>
<?php showFooter(); ?>