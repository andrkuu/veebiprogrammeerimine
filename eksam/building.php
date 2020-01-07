<?php
require("../../../config.php");
require("functions_building.php");


require("classes/Session.class.php");
SessionManager::sessionStart("vp",0,"/~andrekuu/","localhost");

header('Content-Type: text/html; charset=utf-8');

$selectedGender = 0;
$selectedRole = 0;

/*
if(isset($_POST["change"])){
    var_dump($_POST["change"]);
    print("muutus");
}*/

if(isset($_POST["refreshTable"])){
   # $selectedGender = $_POST["filter_gender"];
    if(isset($_POST["filter_gender"])){
        $selectedGender = $_POST["filter_gender"];
        $selectedRole = $_POST["filter_role"];
        echo "gender:".$selectedGender;
        echo "role:".$selectedRole;
 ;
    }

}

if(isset($_POST["submitLoad"])){

    $err = insert_person( $_POST["first_name"], $_POST["last_name"], $_POST["role"], $_POST["gender"]);
    if ($err){
        echo "Sisestatud";
    }else{
        echo $err;
    }
}

?>
<script>

    function removeFunction(clicked_id) {
        console.log(clicked_id);
    }

</script>
<body>
<?php
echo "<h1>Hoone haldamine</h1>";
?>
<p>See veebileht on hoone haldamiseks !</p>
<hr>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

    Inimesi majas:

    <?php
    echo(get_count());

    echo(get_persons($selectedGender,$selectedRole));

    ?>
    <br>
    <label>Sugu:</label>
    <select name='filter_gender' value='1'>
        <option value="0">Kõik</option>
        <option value="1">Mees</option>
        <option value="2">Naine</option>
    </select>

    <label>Roll:</label>
    <select name='filter_role'>
        <option value="0">Kõik</option>
        <?php echo(get_roles());?>
    </select>

    <br>
    <input name="refreshTable" type="submit" value="Värskenda">
    <br>
    <label>Eesnimi:</label>
    <input name="first_name" type="text" ><br>
    <br>
    <label>Perenimi:</label>
    <input name="last_name" type="text" ><br>
    <br>
    <label>Roll:</label>
    <select name='role'>
    <?php echo(get_roles());?>
    </select>
    <br>
    <label>Sugu:
    <br>
    <input name="gender" type="radio" value = "1">Mees</input><br>
    <input name="gender" type="radio" value = "2">Naine</input><br>
    </label>
    <br>
    <br>
    <input name="submitLoad" type="submit" value="Sisesta inimene"><span></span>
</form>
</body>
</html>