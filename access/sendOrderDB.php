<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    if(isset($_POST["fromCatering"])){
        $sql = "SELECT * FROM cart WHERE idUser = '".$_SESSION["user"]."' AND cart.catering = 1;";
    }
    else{
        $sql = "SELECT * FROM cart WHERE idUser = '".$_SESSION["user"]."' AND cart.catering = 0;";
    }
    $cart = $conn->query($sql);
    $cart = mysqli_fetch_assoc($cart); 
    if(isset($cart["idDish"])){
        if(isset($_POST["fromCatering"])){
            $data = $_POST["day"];
            $time = $_POST["hours"].":00";
            $date = $data ." ".$time;
            $conn->query('  INSERT INTO forder 
                            SET 
                            delivery = 0,
                            orderStatus = "1",
                            idUser = "'.$_SESSION["user"].'" ,  
                            firstName = "'.$_POST["name"].'" ,
                            surname = "'.$_POST["surname"].'" , 
                            reservations = "'.$_POST["reservations"].'" , 
                            note = "'.$_POST["notes"].'",
                            dateAndTimeDelivered = "'.$date.'";');
            $order = $conn->query('SELECT id FROM forder WHERE delivery = 0 ORDER BY dateAndTimePay DESC, idUser = "'.$_SESSION["user"].'";');
        }
        else{
            $conn->query('  INSERT INTO forder 
                            SET 
                            delivery = 1,
                            orderStatus = "1",
                            idUser = "'.$_SESSION["user"].'" ,  
                            firstName = "'.$_POST["name"].'" ,
                            surname = "'.$_POST["surname"].'" , 
                            via = "'.$_POST["via"].'" , 
                            civ = "'.$_POST["civ"].'" , 
                            cap = "'.$_POST["cap"].'";');
            $order = $conn->query('SELECT id FROM forder WHERE delivery = 1 ORDER BY dateAndTimePay DESC, idUser = "'.$_SESSION["user"].'";');
        }
        $order = mysqli_fetch_assoc($order);
        $newOrderID = $order["id"];

        if(isset($_POST["fromCatering"])){
            $dishs = $conn->query('SELECT * FROM cart WHERE idUser = "'.$_SESSION["user"].'" AND cart.catering = 1;');
        }
        else{
            $dishs = $conn->query('SELECT * FROM cart WHERE idUser = "'.$_SESSION["user"].'" AND cart.catering = 0;');
        }
        while($row = $dishs->fetch_assoc()){
            $conn->query('INSERT INTO  orderedfood VALUES ("'.$newOrderID.'","'.$row["idDish"].'","'.$row["quantity"].'");');
            if(isset($_POST["fromCatering"])){
                $conn->query('DELETE cart FROM cart WHERE idUser = "'.$_SESSION["user"].'"AND idDish = "'.$row["idDish"].'" AND cart.catering = 1;');
            }
            else{
                $conn->query('DELETE cart FROM cart WHERE idUser = "'.$_SESSION["user"].'"AND idDish = "'.$row["idDish"].'" AND cart.catering = 0;');
            }
        }
        $conn->close();
        $_SESSION["bigNews"] = "news";
        header('Location: ../home.php');
        exit;
    }
    else{
        $conn->close();
        if(isset($_POST["fromCatering"])){
            header('Location: ../catering.php');
        }
        else{
            header('Location: ../cart.php');
        }
        exit; 
    }
