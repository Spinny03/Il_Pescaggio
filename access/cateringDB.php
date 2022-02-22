<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    $inCart = $_POST["catering"];
    $dishID = $_POST["dish"];

    if($inCart == "cart"){
        $sql = ' DELETE FROM cart WHERE idUser="'.$_SESSION["user"].'" AND cart.idDish="'.$dishID.'" AND catering = 1;';
        $conn->query($sql); 
    }
    else{
        $sql = ' INSERT INTO cart (`idUser`, `idDish`, `quantity`, `catering`) VALUES ("'.$_SESSION["user"].'","'.$dishID.'",1,1);';
        $conn->query($sql); 
    }

    $conn->close();

    header('Location: ../catering.php');
    exit;
?>