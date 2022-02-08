<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");


    if(isset($_POST["add"])){
        $sql = 'SELECT dish.dishName FROM `cart`, `dish` WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dishName="'.$_POST["add"].'";';  
        $result = $conn->query($sql); 
        $result = mysqli_fetch_assoc($result);
        if(!empty($result["dishName"])){
            $sql1 = ' UPDATE cart, dish SET quantity = quantity + 1 WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dishName="'.$_POST["add"].'";';
            $conn->query($sql1); 
        }
        else{
            $sql1 = 'SELECT id FROM `dish` WHERE dishName="'.$_POST["add"].'";';
            $result1 = $conn->query($sql1); 
            $result1 = mysqli_fetch_assoc($result1);
            $sql2 = ' INSERT INTO cart (`idUser`, `idDish`, `quantity`) VALUES ("'.$_SESSION["user"].'","'.$result1["id"].'",1);';
            $conn->query($sql2); 
        }
    
    }
    if(isset($_POST["less"])){
        $sql = 'SELECT dish.dishName, quantity FROM `cart`, `dish` WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dishName="'.$_POST["less"].'";';  
        $result = $conn->query($sql); 
        $result = mysqli_fetch_assoc($result);
        if($result["dishName"]==$_POST["less"] && $result["quantity"] > 0){
            $sql = ' UPDATE cart, dish SET quantity = quantity - 1 WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dishName="'.$_POST["less"].'";';
            $conn->query($sql); 
        }
    }
   
    $conn->close();
    header("Location: ../home.php");
    exit;
?>