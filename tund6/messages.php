<?php
require("../../../config.php");
require("functions_main.php");
require("functions_user.php");
require("functions_message.php");

if(!isset($_SESSION["userId"])){
    header("Location: myindex.php");
    exit();
}

$notice = null;

$result = getInfo($_SESSION["userId"]);

$userName = $_SESSION["userFirstName"]." ".$_SESSION["userLastName"];

  if(isset($_POST["submitMessage"])){
      if(!empty(test_input($_POST["message"]))){
        $notice = storeMessage(test_input($_POST["message"]));
      }
      else{
          $notice = "Tühja sõnumit ei salvestata";
      }
  }
//$messages = readAllMessages();
$messages = readMyMessages();


?>
<body>
  <?php
    echo "<h1>" .$userName ." koolitöö leht</h1>";
  ?>
  <p>See leht on loodud koolis õppetöö raames
  ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <p><a href="?logout=1">Logi välja!</a> | Tagasi <a href="home.php">avalehele</a></p>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	  <label>Minu sõnum</label><br>
	  <textarea rows="5" cols="50" name="message" placeholder="Lisa siia oma sõnum ..."></textarea>
	  <br>
	  <input name="submitMessage" type="submit" value="Salvesta sõnum"><span><?php echo $notice; ?></span>
      <?php echo $messages; ?>
	</form>

</body>
</html>




