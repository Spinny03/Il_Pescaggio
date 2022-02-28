<?php 
    session_start();
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    if(isset($_POST["confirm"])){
        if($_POST["confirm"] == "True"){
            $conn->query('UPDATE FOrder SET dateAndTimePay = dateAndTimePay, orderStatus = 2 WHERE id = "'.$_POST["idOrder"].'";');
        }
        else{
            $conn->query('UPDATE FOrder SET dateAndTimePay = dateAndTimePay, orderStatus = -1 WHERE id = "'.$_POST["idOrder"].'";');      
        }
    }
    elseif(isset($_POST["send"]) && isset($_POST["rider"])){
        $conn->query('UPDATE FOrder SET dateAndTimePay = dateAndTimePay, orderStatus = 3, idRider="'.$_POST["rider"].'" WHERE id = "'.$_POST["idOrder"].'";'); 
        $conn->query('UPDATE rider SET available = 0  WHERE email="'.$_POST["rider"].'";');  
    }
    $conn->query('UPDATE username, FOrder SET registrationDate = registrationDate, notice = 1 WHERE id = "'.$_POST["idOrder"].'" AND idUser=email' ); 
    header("Location: ../adminOrders.php");
    $conn->close();
    exit();
?>