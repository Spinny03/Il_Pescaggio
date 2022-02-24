<?php 
    session_start();
    if($_POST["change"] == "logOUT"){
        if(isset($_COOKIE["user"])){
            unset($_COOKIE['user']);
            setcookie('user', null, -1, '/');
        }
        $_SESSION["user"] = "";
        header("Location: ../index.php");
        exit();
    }

    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");


    if($_POST["change"] == "True" && (!empty($_POST["email"]) || !empty($_POST["name"]) || !empty($_POST["surname"]) || !empty($_POST["tel"]) || !empty($_POST["address1"]) || !empty($_POST["address2"]) || !empty($_POST["postcode"]) || !empty($_POST["nCard"]) || !empty($_POST["changPasw"]))){
        $sql ="UPDATE username SET ";
        if(!empty($_POST["email"])){
            $check = "SELECT email FROM username WHERE email='". $_POST["email"]."';";
            $result = $conn->query($check);
            $result = mysqli_fetch_assoc($result);
            if(!empty($result["email"]) && $result["email"] != $_SESSION["user"]){
                $_SESSION["emailFail"] = True;
                $conn->close();
                header("Location: ../profile.php");
                exit();
            }
            else{
                $sql .= 'email="'.$_POST["email"].'",';
            }
        }

        if(!empty($_POST["name"])){
            $sql .= 'firstName="'.$_POST["name"].'",';   
        }

        if(!empty($_POST["surname"])){
            $sql .= 'surname="'.$_POST["surname"].'",';
        }

        if(!empty($_POST["tel"])){
            $sql .= 'tel="'.$_POST["tel"].'",';
        }

        if(!empty($_POST["address1"])){
            $sql .= 'via="'.$_POST["address1"].'",';
        }

        if(!empty($_POST["address2"])){
            $sql .= 'civ="'.$_POST["address2"].'",';
        }

        if(!empty($_POST["postcode"])){
            $sql .= ' cap="'.$_POST["postcode"].'",';
        }

        if(!empty($_POST["nCard"])){
            $sql .= 'nCard="'.$_POST["nCard"].'",';
        }

        if(!empty($_POST["changPasw"])){
            $sql .= 'changPasw="'.hash("sha256",$_POST["changPasw"]).'", ';
        }
        $sql .= ' registrationDate=registrationDate WHERE email="'.$_SESSION["user"].'"';
        echo $sql;
        $conn->query($sql);
    }
    header("Location: ../profile.php");
    $conn->close();
    exit();
?>