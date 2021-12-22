<?php
require_once "../src/config.php";

global $lang;

showHead($lang["resetPassword"], ['assets/css/pwreset.css']);
?>
    <body>
        <div id="container">
            <div id="resetImg">
                <a href="index.php">
                    <img src="assets/img/logo.png" alt="Logo MyFlix">
                </a>
            </div>
            <div id="resetMsg">
                <p id="msg"><?= $lang["resetPw"] ?></p>
                <h1><a href="mailto:administrationr@myflix.com">administration@myflix.com</a></h1>
            </div>
            
            <a href="login.php"><?= $lang["returnTxt"] ?></a>

        </div>
    </body>
<?php showFooter(); ?>