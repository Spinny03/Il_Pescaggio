<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");


    if(isset($_POST["add"])){
        $name = $_POST["add"];
        $sql = 'SELECT dish.id, dishType FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$_POST["add"].'";';  
        $result = $conn->query($sql); 
        $result = mysqli_fetch_assoc($result);
        if(!empty($result["id"])){
            $sql1 = ' UPDATE cart, dish SET quantity = quantity + 1 WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$_POST["add"].'";';
            $conn->query($sql1); 
        }
        else{
            $sql = 'SELECT id, dishType FROM dish WHERE dish.id="'.$_POST["add"].'";';
            $result = $conn->query($sql); 
            $result = mysqli_fetch_assoc($result);
            $sql2 = ' INSERT INTO cart (`idUser`, `idDish`, `quantity`) VALUES ("'.$_SESSION["user"].'","'.$result["id"].'",1);';
            $conn->query($sql2); 
        }
    
    }
    elseif(isset($_POST["less"])){
        $name = $_POST["less"];
        $sql = 'SELECT dish.id, quantity, dishType FROM `cart`, `dish` WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$_POST["less"].'";';  
        $result = $conn->query($sql); 
        $result = mysqli_fetch_assoc($result);
        if($result["id"]==$_POST["less"] && $result["quantity"] > 1){
            $sql = ' UPDATE cart, dish SET quantity = quantity - 1 WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$_POST["less"].'";';
            $conn->query($sql); 
        }
        elseif($result["quantity"] <= 1){
            $sql = ' DELETE cart FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$_POST["less"].'";';
            $conn->query($sql); 
        }
    }
    elseif(isset($_POST["del"])){
        $sql = ' DELETE cart FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$_POST["del"].'";';
        $conn->query($sql); 
        header("Location: ../cart.php");
        exit;
    }
    
    $_SESSION["typefood"] = $result["dishType"];

    $conn->close();
    
    if(isset($_POST["cameFromCart"])){
        $_POST["cameFromCart"] == 0;
        header("Location: ../cart.php");
        exit;
    }
    header('Location: ../home.php#'.$name);
    exit;
?>