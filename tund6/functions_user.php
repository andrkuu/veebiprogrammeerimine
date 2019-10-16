<?php

session_start();

function signUp($name, $surname, $email, $gender, $birthDate, $password){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);

	$stmt = $conn->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
	echo $conn->error;
	
	//tekitame parooli räsi (hash) ehk krüpteerime
	$options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	
	$stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
	
	if($stmt->execute()){
		$notice = "Kasutaja salvestamine õnnestus!";
	} else {
		$notice = "Kasutaja salvestamisel tekkis tehniline tõrge: " .$stmt->error;
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}

function signIn($email, $password){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT password FROM vpusers WHERE email=?");
	echo $conn->error;
	$stmt->bind_param("s", $email);
	$stmt->bind_result($passwordFromDb);
	if($stmt->execute()){
		//kui päring õnnestus
	  if($stmt->fetch()){
		//kasutaja on olemas
		if(password_verify($password, $passwordFromDb)){
		  //kui salasõna klapib
		  $stmt->close();
		  $stmt = $conn->prepare("SELECT id,firstname, lastname FROM vpusers WHERE email=?");
		  echo $conn->error;
		  $stmt->bind_param("s", $email);
		  $stmt->bind_result($idFromDb,$firstnameFromDb, $lastnameFromDb);
		  $stmt->execute();
		  $stmt->fetch();
		  //Enne sisselogitud lehtedele jõudmist, sulgeme andmebaasi ühendused
		  $notice = "Sisse logis " .$firstnameFromDb ." " .$lastnameFromDb ."!";


		  //Salvestame kasutaja info sessioonimuutujasse
          $_SESSION["userFirstName"] = $firstnameFromDb;
          $_SESSION["userLastName"] = $lastnameFromDb;
          $_SESSION["userId"] = $idFromDb;

		  $stmt->close();
		  $conn->close();

		  header("Location: home.php");
		  exit();


		  
		} else {
		  $notice = "Vale salasõna!";
		}//kas password_verify
	  } else {
		$notice = "Sellist kasutajat (" .$email .") ei leitud!";
		//kui sellise e-mailiga ei saanud vastet (fetch ei andnud midagi), siis pole sellist kasutajat
	  }//kas fetch õnnestus
	} else {
	  $notice = "Sisselogimisel tekkis tehniline viga!" .$stmt->error;
	  //veateade, kui execute ei õnnestunud
	}//kas execute õnnestus
	
	$stmt->close();
	$conn->close();
	return $notice;
  }//sisselogimine lõppeb

function saveInfo($description, $bgColor, $txtColor){

    $notice = "";
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT id FROM vpuserprofiles WHERE userid=?");
    echo $conn->error;
    $stmt->bind_param("i", $_SESSION["userId"]);
    $stmt->bind_result($idFromDb);
    $stmt->execute();

    if($stmt->fetch()){
        //profiil juba olemas, uuendame

        $stmt->close();
        $stmt = $conn->prepare("UPDATE vpuserprofiles SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid=?");
        echo $conn->error;
        $stmt->bind_param("sssi", $description, $bgColor, $txtColor, $_SESSION["userId"]);
        if($stmt->execute()){
            $_SESSION["bgColor"] = $bgColor;
            $_SESSION["txtColor"] = $txtColor;
            $notice = "Profiil edukalt uuendatud!";
        } else {
            $notice = "Profiili uuendamisel tekkis tõrge! " .$stmt->error;
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor, picture) VALUES(?,?,?,?,?)");


        echo $conn->error;


        $stmt->bind_param("isssi", $_SESSION["userId"], $description, $bgcolor, $textcolor, $picture);

        if($stmt->execute()){
            $notice = "Info salvestamine õnnestus!";
        } else {
            $notice = "Kasutaja info salvestamisel tekkis tehniline tõrge: " .$stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
    return $notice;
}

function getInfo($userId){
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);


    $stmt = $conn->prepare("SELECT description,bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
    echo $conn->error;
    $stmt->bind_param("s", $userId);
    $stmt->bind_result($description,$bgColor, $txtColor);
    $stmt->execute();
    $stmt->fetch();

    $_SESSION["bgColor"] = $bgColor;
    $_SESSION["txtColor"] = $txtColor;
    $_SESSION["description"] = $description;

    $result["bgColor"] = $bgColor;
    $result["txtColor"] = $txtColor;
    $result["description"] = $description;

    $stmt->close();
    $conn->close();

    /*
    if($stmt->execute()){
        $notice = "Info lugemine õnnestus!";

        print_r($result);
    } else {
        $notice = "Kasutaja info salvestamisel tekkis tehniline tõrge: " .$stmt->error;
    }*/

    return $result;
}