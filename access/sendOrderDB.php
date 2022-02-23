<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    $sql ="SELECT * FROM cart WHERE idUser='".$_SESSION["user"]."' AND cart.catering = 0;";
    $cart = $conn->query($sql);
    $cart = mysqli_fetch_assoc($cart); 
    if(isset($cart["idDish"])){
        $conn->query('INSERT INTO forder 
                        SET delivery=1 ,
                        idUser="'.$_SESSION["user"].'" ,  
                        firstName="'.$_POST["name"].'" ,
                        surname="'.$_POST["surname"].'"  , 
                        via="'.$_POST["via"].'" , 
                        civ="'.$_POST["civ"].'" , 
                        cap="'.$_POST["cap"].'" ;');
        $order = $conn->query('SELECT id FROM forder WHERE delivery=1 ORDER BY dateAndTimePay DESC, idUser="'.$_SESSION["user"].'";');
        $order = mysqli_fetch_assoc($order);
        $newOrderID = $order["id"];
        $dishs = $conn->query('SELECT * FROM cart WHERE idUser="'.$_SESSION["user"].'" AND cart.catering = 0;');
        while($row = $dishs->fetch_assoc()){
            $conn->query('INSERT INTO  orderedfood VALUES ("'.$newOrderID.'","'.$row["idDish"].'","'.$row["quantity"].'");');
            $conn->query('DELETE cart FROM cart WHERE idUser="'.$_SESSION["user"].'"AND idDish="'.$row["idDish"].'" AND cart.catering = 0;');
        }
        $conn->close();
        header('Location: ../home.php');
        exit;
    }
    else{
        $conn->close();
        header('Location: ../cart.php');
        exit; 
    }
?>