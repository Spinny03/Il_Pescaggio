<?php 
    session_start();

    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    echo $_POST["name"]."<br>";
    echo $_POST["surname"]."<br>";
    echo $_POST["email"]."<br>";
    echo $_POST["tel"]."<br>";
    echo $_POST["address1"]."<br>";
    echo $_POST["address2"]."<br>";
    echo $_POST["postcode"]."<br>";
    echo $_POST["nCard"]."<br>";
    echo $_POST["changPasw"]."<br>";
    //header("Location: ../profile.php");
    $conn->close();
    exit();
?>