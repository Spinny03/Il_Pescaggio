<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");


    if(isset($_POST["edit"])){
        $name = $_POST["add"];
        $sql = 'SELECT dish.dishName, dishType FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dishName="'.$_POST["add"].'";';  
        $result = $conn->query($sql); 
        $result = mysqli_fetch_assoc($result);
        if(!empty($result["dishName"])){
            $sql1 = ' UPDATE cart, dish SET quantity = quantity + 1 WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dishName="'.$_POST["add"].'";';
            $conn->query($sql1); 
        }
        else{
            $sql = 'SELECT id, dishType FROM dish WHERE dishName="'.$_POST["add"].'";';
            $result = $conn->query($sql); 
            $result = mysqli_fetch_assoc($result);
            $sql2 = ' INSERT INTO cart (`idUser`, `idDish`, `quantity`) VALUES ("'.$_SESSION["user"].'","'.$result["id"].'",1);';
            $conn->query($sql2); 
        }
    
    }
    elseif(isset($_POST["del"])){
        $sql = 'SELECT id FROM dish WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dishName="'.$_POST["add"].'";';  
        $result = $conn->query($sql); 
        $result = mysqli_fetch_assoc($result);
        $sql = ' DELETE cart FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND dishName="'.$_POST["del"].'";';
        $conn->query($sql); 
    }
    
    $_SESSION["typefood"] = $result["dishType"];

    $conn->close();
    header("Location: ../dishs.php#pform");
    exit;
?>