<?php

//sessiooni kasutamise algatamine
//session_start();
//var_dump($_SESSION);

header('Content-Type: text/html; charset=utf-8');

function insert_person($first_name, $last_name, $role_name, $gender){

    $ret = False;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("INSERT INTO BUILDING (first_name, last_name, role, gender) VALUES ((?), (?),(?),(?))");

    $stmt->bind_param("sssi", $first_name, $last_name, $role_name, $gender);
    if($stmt->execute()){
        $ret = True;
    }else{
        $ret = "Viga: " .$stmt->error. $role_name;
    }

    $stmt->close();

    $conn->close();

    return  $ret;

}

function get_count(){
    $roleHTML = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT count(*) FROM BUILDING");
    echo $conn->error;
    $stmt->bind_result($personcount);
    $stmt->execute();

    $stmt->fetch();
    #$roleHTML .= "</select>";
    $stmt->close();
    $conn->close();
    return $personcount;
}

function get_roles(){
    $roleHTML = null;

    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT id, role_name FROM ROLES ORDER BY role_name");
    echo $conn->error;
    $stmt->bind_result($idFromDb,$roleFromDb);
    $stmt->execute();
    #$roleHTML .= "<select name='role'>";


    while($stmt->fetch()){
        $roleHTML .= '<option value="' .$idFromDb .'"';

        $roleHTML .= ">" .$roleFromDb . "</option> \n";
    }
    #$roleHTML .= "</select>";
    $stmt->close();
    $conn->close();
    return $roleHTML;
}

function get_persons($gender,$role){
    $truckHTML = null;



    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    //SELECT first_name, last_name, role, gender FROM BUILDING WHERE role IN(SELECT id FROM ROLES WHERE role_name = "Oppejoud") ORDER BY last_name
    echo "sugu:".$gender;
    echo "roll:".$role;

    if(intval($gender) == 0){

        if(intval($role) == 0){
            $stmt = $conn->prepare("SELECT id, first_name, last_name, (SELECT role_name FROM ROLES WHERE id = BUILDING.role), gender FROM BUILDING ORDER BY last_name");
        }
        else{
            $stmt = $conn->prepare("SELECT id, first_name, last_name, (SELECT role_name FROM ROLES WHERE id = ?) AS role, gender FROM BUILDING WHERE role = ? ORDER BY last_name");

            $stmt->bind_param("ii", $role,$role);
        }
    }
    else{

        if(intval($role) == 0){
            $stmt = $conn->prepare("SELECT id, first_name, last_name, (SELECT role_name FROM ROLES WHERE id = BUILDING.role), gender FROM BUILDING WHERE gender=? ORDER BY last_name");
            $stmt->bind_param("i", $gender);
        }
        else{
            $stmt = $conn->prepare("SELECT id, first_name, last_name, (SELECT role_name FROM ROLES WHERE id = ?) AS role, gender FROM BUILDING WHERE gender=? AND role = ? ORDER BY last_name");

            $stmt->bind_param("iii", $role,$gender, $role);
        }


    }


    echo $conn->error;
    $stmt->bind_result($id,$firstName,$lastName,$role,$gender);
    if($stmt->execute()){
        #echo("yes");
    }
    else{
        echo($stmt->error);
    };
    $truckHTML .= "<table>";
    $truckHTML .= "
    <tr>
        <th>Eesnimi</th>
        <th>Perenimi</th>
        <th>Roll</th>
        <th>Sugu</th>
        <th></th>
    </tr>";


    while($stmt->fetch()){

        #echo("test");
        if ($gender == 1){
            $gender = "Mees";
        }elseif ($gender == 2){
            $gender = "Naine";
        }
        $truckHTML .= "
        <tr>
            <td>".$firstName."</td>
            <td>".$lastName."</td>
            <td>".$role."</td>
            <td>".$gender."</td>
            <td><button id =\"".$id."\" type='button' name=\"change\" onclick=\"removeFunction(this.id)\" >Eemalda</button></td>
        </tr>";
    }
    $truckHTML .= "</table>";
    $stmt->close();
    $conn->close();
    return $truckHTML;
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
		  $stmt = $conn->prepare("SELECT id, firstname, lastname FROM vpusers WHERE email=?");
		  echo $conn->error;
		  $stmt->bind_param("s", $email);
		  $stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);
		  $stmt->execute();
		  $stmt->fetch();
		  $notice = "Sisse logis " .$firstnameFromDb ." " .$lastnameFromDb ."!";
		  
		  //salvestame kasutaja kohta loetud info sessioonimuutujatesse
		  $_SESSION["userId"] = $idFromDb;
		  $_SESSION["userFirstname"] = $firstnameFromDb;
		  $_SESSION["userLastname"] = $lastnameFromDb;
		  
		  //loeme kasutajaprofiili
		  $stmt->close();
		  $stmt = $conn->prepare("SELECT bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
		  echo $conn->error;
		  $stmt->bind_param("i", $_SESSION["userId"]);
		  $stmt->bind_result($bgColorFromDb, $txtColorFromDb);
		  $stmt->execute();
		  if($stmt->fetch()){
			$_SESSION["bgColor"] = $bgColorFromDb;
	        $_SESSION["txtColor"] = $txtColorFromDb;
		  } else {
		    $_SESSION["bgColor"] = "#FFFFFF";
	        $_SESSION["txtColor"] = "#000000";
		  }
		  
		  //enne sisselogitutele mõeldud lehtedele jõudmist sulgeme andmebaasi ühendused
		  $stmt->close();
	      $conn->close();
		  //liigume soovitud lehele
		  header("Location: home.php");
		  //et siin rohkem midagi ei tehtaks
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
  }
  
