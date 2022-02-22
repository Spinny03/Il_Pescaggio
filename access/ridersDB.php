<?php 
    session_start();
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");


    if(isset($_POST["del"])){
        $sql = 'DELETE rider FROM rider WHERE id="'.$_POST["del"].'";';
        $conn->query($sql);
    }
    elseif(isset($_POST["change"]) && $_POST["change"]  == "True"){
        $sql = "";
        if(!empty($_POST["name"])){
            $sql .= 'riderName="'.$_POST["name"].'",';   
        }

        if(!empty($_POST["surname"])){
            $sql .= 'riderSurname="'.$_POST["surname"].'",';
        }

        $sql = substr($sql, 0, -1);
        $conn->query('UPDATE rider SET '.$sql.' WHERE id="'.$_POST["idRider"].'";');
    }
    elseif(isset($_POST["change"]) && $_POST["change"] == "add"){
        $sql = "";
        if(!empty($_POST["name"])){
            $sql .= 'riderName="'.$_POST["name"].'",';   
        }

        if(!empty($_POST["surname"])){
            $sql .= 'riderSurname="'.$_POST["surname"].'",';
        }

        $sql = substr($sql, 0, -1);
        $conn->query("INSERT INTO rider SET ".$sql.";");

    }
    header("Location: ../riders.php");
    $conn->close();
    exit();
?>