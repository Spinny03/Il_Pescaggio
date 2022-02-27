<?php 
    session_start(); 
    if(!empty($_SESSION["rider"])){
        header("Location: ridersOrders.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="css/formStyles.css">
        <link rel="stylesheet" href="css/scrollBarStyles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>Il Pescaggio</title>
    </head>
    <body>
        <?php 
            if(isset($_SESSION["emailFail"]) && $_SESSION["emailFail"]){
                echo'<style>
                        input[name="email"]{
                            background-color: rgba(255, 78, 113, 0.7);
                        }
                    </style>';
                $_SESSION["emailFail"] = False;
            }
            if(isset($_SESSION["paswFail"]) && $_SESSION["paswFail"]){
                echo'<style>
                        input[name="psw"]{
                            background-color: rgba(255, 78, 113, 0.7);
                        }
                    </style>';
                $_SESSION["paswFail"] = False;
            }
        ?>
        <div class="container">
            <div class="left">
                <img src="images/logo.png" alt="logo" id="logo">
                <div class="log">
                    <h1>Area riders</h1>
                    <span>Accedi con i dati che ti sono stati assegnati.</span>
                    <form action="access/loginRidersDB.php" method="POST">
                        <label for="email"><b>Email</b></label>
                        <input type="text" placeholder="nome@esempio.com" name="email" 
                            <?php
                                if(isset($_SESSION["riderLogin"])){
                                    echo "value='".$_SESSION["riderLogin"]."'";
                                }
                            ?> 
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>

                        <label for="psw"><b>Password</b></label>
                        <input type="password" placeholder="min. 8 caratteri" name="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Deve contenere almeno un numero e una lettera maiuscola e minuscola e almeno 8 o più caratteri" required>
                        <button type="submit" name="login" class="logbtn">Accedi</button>
                    </form>
                    <div class="pswDiv">
                        <a href="#" class="Link">Password dimenticata?<br><br></a>
                        <a href="index.php" class="Link">Area comune</a>
                    </div>
                </div>  
            </div>
            <div class="right">

            </div>
        </div>
    </body>
</html>