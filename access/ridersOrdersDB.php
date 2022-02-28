<?php 
    session_start();
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    if(isset($_POST["delivered"])){
        $data = date("Y-m-d");
        $time = date("H:i:s");
        $date = $data ." ".$time;
        $conn->query('UPDATE rider SET available = 1 WHERE email = "'.$_SESSION["rider"].'";'); 
        $conn->query('UPDATE FOrder SET dateAndTimePay = dateAndTimePay, dateAndTimeDelivered = "'.$date.'", orderStatus = 4 WHERE id = "'.$_POST["idOrder"].'";');
    }
    header("Location: ../ridersOrders.php");
    $conn->close();
    exit();
?>