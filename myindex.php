<?php
	$userName = "Andreas Kuuskaru";
	$fullTimeNow = date("d.m.Y");
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
		echo $userName;
		echo "Lehe avamise hetkel oli ".$fullTimeNow.".";
	?>    
</body>

</html>
