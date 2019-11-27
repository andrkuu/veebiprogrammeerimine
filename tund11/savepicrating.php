<?php
    $rating = $_REQUEST["rating"];

    echo "Kahekordne hinne on: ".$rating * 2;

    $notice = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("INSERT INTO vpphotoratings (photoid, userid, rating) VALUES(?,?,?)");
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
