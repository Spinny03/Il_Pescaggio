<?php 
    session_start();
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    if((!empty($_POST["name"]) || !empty($_POST["price"]) || !empty($_POST["description"]))){
        $sql ="UPDATE dish SET ";

        if(!empty($_POST["name"])){
            $sql .= 'dishName="'.$_POST["name"].'",';   
        }

        if(!empty($_POST["price"])){
            $sql .= 'dishCost="'.$_POST["price"].'",';
        }

        if(!empty($_POST["description"])){
            $sql .= 'description="'.$_POST["description"].'",';
        }

        /*if(!empty($_POST["type"])){
            $sql .= 'foodtype="'.$_POST["type"].'",';
        }*/

        $sql = substr($sql, 0, -1);
        $sql .= ' WHERE id="'.$_POST["idDish"].'"';
        echo $sql;
        $conn->query($sql);
    }
    header("Location: ../dishs.php");
    $conn->close();
    exit();
?>