<?php 
    session_start();

    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE my_ilpescaggio");


    $email = $_POST["email"];
    $_SESSION["riderLogin"] = $email;
    $psw = hash("sha256",$_POST["psw"]);

    //doppia query almeno possiamo dare come feedback l'errore se username o pasw 
    $result1 = $conn->query("SELECT email FROM rider WHERE email = '". $email."';");
    $result1 = mysqli_fetch_assoc($result1);

    if($result1['email'] == $email){

        $result2 = $conn->query("SELECT pasw FROM rider WHERE email = '". $email."' AND pasw = '". $psw."';");
        $result2 = mysqli_fetch_assoc($result2);
        
        if($result2['pasw'] == $psw ){
            $_SESSION["rider"] = $email;
            header("Location: ../ridersOrders.php");
        }
        else{
            $_SESSION["paswFail"] = True;
            header("Location: ../ridersLogin.php");
        } 

    }
    else{
        $_SESSION["emailFail"] = True;
        $_SESSION["paswFail"] = True;
        header("Location: ../ridersLogin.php");
    }

    $conn->close();
    exit();
?>