<?php 
    session_start(); 
    if(!isset($_SESSION["user"]) || empty($_SESSION["user"])){
        header("Location: index.php");
        exit();
    }
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");
    $sql = 'SELECT dish.dishName, quantity FROM `cart`, `dish` WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish;';
    $result = $conn->query($sql); 

    echo "<table>"; // start a table tag in the HTML

    while($row = $result->fetch_assoc()){   
    echo "<tr><td>" . htmlspecialchars($row['dishName']) . "</td><td>" . htmlspecialchars($row['quantity']) . "</td></tr>"; 
    }

    echo "</table>"; 

    $conn->close();
?>