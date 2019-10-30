<?php
require("../../../config.php");
require("functions_main.php");
require("functions_user.php");

if(!isset($_SESSION["userId"])){
    header("Location: myindex.php");
    exit();
}

$result = getInfo($_SESSION["userId"]);


if(isset($_POST["saveProfile"])){
    print_r(saveInfo($_POST["description"],$_POST["bgColor"],$_POST["txtColor"]));
    $mybgcolor = $_POST["bgColor"];
    $mytxtcolor = $_POST["txtColor"];
    $mydescription = $_POST["description"];

}
//logout
if(isset($_GET["logout"])){
    //sessioon kinni
    session_unset();
    session_destroy();
    header("Location: myindex.php");
    exit();
}

$userName = $_SESSION["userFirstName"]." ".$_SESSION["userLastName"];


require ("header.php");

echo "<h1>" .$userName .", veebiprogrammeerimine 2019</h1>";
?>
<p>Olete sisseloginud! Logi <a href="?logout=1">välja</a></p>
<p>See veebileht on valminud õppetöö käigus ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>


<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Minu kirjeldus</label><br>
    <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea>
    <br>
    <label>Minu valitud taustavärv: </label><input name="bgColor" type="color" value="<?php echo $mybgcolor; ?>"><br>
    <label>Minu valitud tekstivärv: </label><input name="txtColor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
    <input name="saveProfile" type="submit" value="Salvesta profiil">
</form>