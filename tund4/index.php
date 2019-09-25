<?php
require("../../../config.php");
require("../getsql.php");


if(isset($_POST["submitFilm"])){
    if(!empty($_POST["filmTitle"])){
        storeFilmINfo(
                $_POST["filmTitle"],
                $_POST["filmYear"],
                $_POST["filmDuration"],
                $_POST["filmGenre"],
                $_POST["filmStudio"],
                $_POST["filmDirector"]);
    }


}

?>


<!DOCTYPE html>
<html lang="ET">
<head>
    <meta charset="UTF-8">
    <title>Veebi programeerimine 2019</title>
</head>

<body>

<form method="post">
    <label>Filmi Pealkiri</label>
    <input type="text" name="filmTitle">
    <br>
    <label>Filmi tootmisaasta</label>
    <input type="number" min="1912" max="2019" value="2019" name="filmYear">
    <br>
    <label>Filmi kestus (min)</label>
    <input type="number" min="1" max="300" value="80" name="filmDuration">
    <br>
    <label>Filmi zanr</label>
    <input type="text" name="filmGenre">
    <br>
    <label>Filmi Stuudio</label>
    <input type="text" name="filmStudio">
    <br>
    <label>Filmi Lavastaja</label>
    <input type="text" name="filmDirector">
    <br>
    <input type="submit" value="Talleta filmi info" name="submitFilm">
</form>

</body>

</html>
