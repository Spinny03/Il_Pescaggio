<?php 
    session_start();

    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE my_ilpescaggio");


    $email = $_POST["email"];
    $_SESSION["userLogin"] = $email;
    $psw = hash("sha256",$_POST["psw"]);

    //doppia query almeno possiamo dare come feedback l'errore se username o pasw 
    $result1 = $conn->query("SELECT email FROM username WHERE email = '". $email."';");
    $result1 = mysqli_fetch_assoc($result1);

    if($result1['email'] == $email){

        $result2 = $conn->query("SELECT pasw FROM username WHERE email = '". $email."' AND pasw = '". $psw."';");
        $result2 = mysqli_fetch_assoc($result2);
        
        if($result2['pasw'] == $psw ){
            if(isset($_POST["remember"]) && $_POST["remember"] == 1){
                $cookie_name = "user";
                $cookie_value = $email;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); 
            } 
            $_SESSION["user"] = $email;
            header("Location: ../home.php");
        }
        else{
            $_SESSION["paswFail"] = True;
            header("Location: ../index.php");
        } 

    }
    else{
        $_SESSION["emailFail"] = True;
        $_SESSION["paswFail"] = True;
        header("Location: ../index.php");
    }

    $conn->close();
    exit();
?>