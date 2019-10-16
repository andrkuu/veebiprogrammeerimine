<?php
require("../../../config.php");
require("functions_main.php");
require("functions_user.php");

if (!isset($_SESSION["userId"])) {
    header("Location: myindex.php");
    exit();
}

//logout
if (isset($_GET["logout"])) {
    //sessioon kinni
    session_unset();
    session_destroy();
    header("Location: myindex.php");
    exit();
}

$userName = $_SESSION["userFirstName"] . " " . $_SESSION["userLastName"];

require("header.php");

echo "<h1>" . $userName . ", veebiprogrammeerimine 2019</h1>";
?>
<p>Olete sisseloginud! Logi <a href="?logout=1">välja</a></p>
<p><a href="userprofile.php">Profiil</a></p>
<p><a href="messages.php">Sõnumid</a></p>
<p>See veebileht on valminud õppetöö käigus ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
