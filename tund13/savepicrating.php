<?php
	//GET meetodiga saadetud väärtused
    require("../../../config.php");
    //session_start();
	$rating = $_REQUEST["rating"];
    $photoId = $_REQUEST["photoId"];
	$userid = $_SESSION["userId"];

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("INSERT INTO vpphotoratings (photoid, userid, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $photoId, $userid, $rating);
    $stmt->execute();
    $stmt->close();
    //küsime uue keskmise hinde
/*
    $stmt=$conn->prepare("SELECT AVG(rating)FROM vpphotoratings WHERE photoid=?");
    $stmt->bind_param("i", $photoId);
    $stmt->bind_result($score);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();*/
    $conn->close();
	
	#echo "Rating:".$rating." photoid ".$photoId." userid ".$userid;