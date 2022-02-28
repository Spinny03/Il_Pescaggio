<?php 
    session_start();
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    if(isset($_POST["del"])){
        $conn->query('UPDATE rider SET fired=1, pasw="fired" WHERE email = "'.$_POST["del"].'";');
    }
    elseif(isset($_POST["change"])){
        $conn->query('INSERT INTO rider SET fired=0, email = "'.$_POST["email"].'", pasw = "'.hash("sha256",$_POST["pasw"]).'", riderName = "'.$_POST["name"].'", riderSurname = "'.$_POST["surname"].'", available = 1');
    }
    header("Location: ../riders.php");
    $conn->close();
    exit();
?>