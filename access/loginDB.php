<?php 
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Il_Pescaggio";
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE " . $dbname);


    $email = $_POST["email"];
    $psw = hash("sha256",$_POST["psw"]);

    //doppia query almeno possiamo dare come feedback l'errore se username o pasw 
    $result1 = $conn->query("SELECT email FROM username WHERE email='". $email."';");
    $result1 = mysqli_fetch_assoc($result1);

    if($result1['email'] == $email){

        $result2 = $conn->query("SELECT pasw FROM username WHERE pasw='". $psw."';");
        $result2 = mysqli_fetch_assoc($result2);
        
        if($result2['pasw'] == $psw ){
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