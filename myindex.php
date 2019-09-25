<?php
    require("../../config.php");
    require("./getsql.php");
	    
    $photoDir = "pildid";

	$userName = "Andreas Kuuskaru";
	$fullTimeNow = date("d.m.Y");

	$photoTypesAllowed = ["image/jpeg","image/png"];

    $hourNow = date("H");
    $partOfDay = "hägune aeg";

    if($hourNow <= 8){
        $partOfDay = "varahommik";
    }
    else if ($hourNow > 8){
        $partOfDay = "hommik";
    }
    else if ($hourNow > 12){
        $partOfDay = "lõuna";
    }
    else if ($hourNow > 15){
        $partOfDay = "õhtu";
    }
    else $partOfDay = "öö";

    $semesterStart = new DateTime("2019-9-2");
    $semesterEnd = new DateTime("2019-12-13");
    $semesterDuration = $semesterStart -> diff($semesterEnd) -> days;

    $today = new DateTime("now");
    $fromSemesterStart = $semesterStart -> diff($today) -> days;

    //echo($fromSemesterStart);
    //echo("<br>".$semesterDuration);

    $semesterInfoHtml = "<p>Info semestri kohta pole kättesaadav.</p>";

    $semesterInfoHtml = "<p>Semester on täies hoos:";
    $semesterInfoHtml .= '<meter min="0"';
    $semesterInfoHtml .= ' max="'.$semesterDuration.'"';
    $semesterInfoHtml .= ' value="'.$fromSemesterStart.'">';
    $semesterInfoHtml .= ' </meter>';
    $semesterInfoHtml .= "</p>";

    //juhusliku foto kasutamine

    $photoList = scandir($photoDir);
    $photoList = array_diff($photoList, array(".", "..") );
    $photoCount = sizeof($photoList);
    $photoList = array_values($photoList);

    foreach ($photoList as $photo) {

        $fullDir = $photoDir."/".$photo;
       $fileInfo = getimagesize($photoDir."/".$photo);
       if (!in_array($fileInfo["mime"],$photoTypesAllowed)){
           unset($photoList[$photo]);
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
        <h1>Andreas Kuuskaru</h1>
        <p>See veebileht on valminud õppetöö käigus ning ei sisalda mingisugust tõsiselt võetavat sisu</p>

	<?php
        echo($semesterInfoHtml);
		echo $userName;
        echo "<p>Lehe avamise hetkel oli " .$fullTimeNow .", " .$partOfDay .".</p>";
        //print_r($semesterStart);
        //print_r( $semesterStart["timezone"]);
        $randompic = $photoList[rand(0,sizeof($photoList)-1)];
        //echo($photoList[rand(0,sizeof($photoList)-1)]);
        if ($photoCount > 0){
            echo("<img src=\"pildid/".$randompic."\" alt=\"penguins paradise\">");
        }
        else
        {
            echo("POLE ÜHTEGI PILTI");
        }

	?>

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
