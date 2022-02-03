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
    $psw = $_POST["psw"];
    $checkPsw = $_POST["Cpsw"];

    $result = $conn->query("SELECT email FROM username WHERE email='". $email."';");
    $result = mysqli_fetch_assoc($result);

    if($result['email'] == $email){
        $_SESSION["exist"]=true;
        header("Location: ../signUp.php");
        $conn->close();
        exit();
    }
    
    if($psw == $checkPsw){
        $conn->query("INSERT INTO username (email, pasw) VALUES ('".$email."', '".hash("sha256",$psw)."');");
        header("Location: ../index.php");
    }
    else{
        $_SESSION["check"]=true;
        header("Location: ../signUp.php");
    }
    $conn->close();
?>