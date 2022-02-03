<?php 
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Il_Pescaggio";

    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }

    $sql = "USE " . $dbname;
    $conn->query($sql);

    $email = $_POST["email"];
    $psw = $_POST["psw"];
    $checkPsw = $_POST["Cpsw"];

    $tablename = "username";
    $sql = "SELECT email FROM ".$tablename." WHERE email='". $email."';";
    echo $sql;
    $result=$conn->query($sql);

    $result = implode("|", mysqli_fetch_assoc($result));

    if(isset($result)){
        $_SESSION["exist"]=true;
        header("Location: ../signUp.php");
        exit();
    }
 
    /*
    if($psw == $checkPsw){
        $sql = "INSERT INTO " . $tablename . " (email, pasw) VALUES ('".$email."', '".hash("sha256",$psw)."');";
        $conn->query($sql);
    }
    else
        */

?>