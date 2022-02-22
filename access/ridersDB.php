<?php 
    session_start();
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    if(isset($_POST["del"]) ){
        $conn->query('DELETE rider FROM rider WHERE id="'.$_POST["del"].'";');
    }
    elseif(isset($_POST["change"]) && $_POST["change"] == "add"){
        $conn->query('INSERT INTO rider SET riderName="'.$_POST["name"].'", riderSurname="'.$_POST["surname"].'", available=1');
    }
    header("Location: ../riders.php");
    $conn->close();
    exit();
?>