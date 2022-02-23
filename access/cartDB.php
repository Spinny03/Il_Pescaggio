<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");


    if(isset($_POST["add"])){
        $name = $_POST["add"];
        $sql = 'SELECT dish.id, dishType FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$name.'" AND cart.catering = 0;';  
        $result = $conn->query($sql); 
        $result = mysqli_fetch_assoc($result);
        if(!empty($result["id"])){
            $sql1 = ' UPDATE cart, dish SET quantity = quantity + 1 WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$name.'" AND cart.catering = 0;';
            $conn->query($sql1); 
        }
        else{
            $sql = 'SELECT dishType FROM dish WHERE dish.id="'.$name.'";';
            $result = $conn->query($sql); 
            $result = mysqli_fetch_assoc($result);
            $sql2 = ' INSERT INTO cart (idUser, idDish, quantity, catering) VALUES ("'.$_SESSION["user"].'","'.$name.'", 1, 0);';
            $conn->query($sql2); 
        }
    
    }
    elseif(isset($_POST["less"])){
        $name = $_POST["less"];
        $sql = 'SELECT dish.id, quantity, dishType FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$name.'" AND cart.catering = 0;';  
        $result = $conn->query($sql); 
        $result = mysqli_fetch_assoc($result);
        if($result["id"]==$name && $result["quantity"] > 1){
            $sql = ' UPDATE cart, dish SET quantity = quantity - 1 WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$name.'" AND cart.catering = 0;';
            $conn->query($sql); 
        }
        elseif($result["quantity"] <= 1){
            $sql = 'SELECT dishType FROM dish WHERE dish.id="'.$_POST["less"].'";';
            $result = $conn->query($sql); 
            $result = mysqli_fetch_assoc($result);
            $sql = ' DELETE cart FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$name.'" AND cart.catering = 0;';
            $conn->query($sql); 
        }
    }
    elseif(isset($_POST["del"])){
        $name = $_POST["del"];
        $sql = ' DELETE cart FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dish.id="'.$name.'" AND cart.catering = 0;';
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