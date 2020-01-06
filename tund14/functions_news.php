<?php

function insertNews($userid, $title, $content, $expire){
    $notice = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("INSERT INTO news (userid, title, content, expire) VALUES(?,?,?,?)");
    echo $conn->error;

    #$userid = 4;
    #$title = "PEalkiri";
    #$content = "Content";
    #$expire = "2019-12-11";
    $stmt->bind_param("isss", $userid, $title, $content, $expire);

    if($stmt->execute()){
        $notice = "Kasutaja salvestamine õnnestus!";
    } else {
        $notice = "Kasutaja salvestamisel tekkis tehniline tõrge: " .$stmt->error;
    }

    $stmt->close();
    $conn->close();
    return $notice;
}


function latestNews($limit){
    $newsHTML = null;
    $today = date("Y-m-d");
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT title, content, added FROM news WHERE expire >=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
    echo $conn->error;
    $stmt->bind_param("si", $today, $limit);
    $stmt->bind_result($titleFromDb, $contentFromDb, $addedFromDb);
    $stmt->execute();
    while ($stmt->fetch()){
        $newsHTML .= "<div> \n";
        $newsHTML .= "\t <h3>" .$titleFromDb ."</h3> \n";
        $addedTime = new DateTime($addedFromDb);
        //$newsHTML .= "\t <p>(Lisatud: " .$addedFromDb .")</p> \n";
        $newsHTML .= "\t <p>(Lisatud: " .$addedTime->format("d.m.Y H:i:s") .")</p> \n";
        $newsHTML .= "\t <div>" .htmlspecialchars_decode($contentFromDb) ."</div> \n";
        $newsHTML .= "</div> \n";
    }
    if($newsHTML == null){
        $newsHTML = "<p>Kahjuks uudiseid pole!</p>";
    }
    $stmt->close();
    $conn->close();
    return $newsHTML;
}


