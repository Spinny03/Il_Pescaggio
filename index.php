<?php 
    session_start(); 
    if(!empty($_SESSION["user"]) || !empty($_COOKIE["user"])){
        if(!empty($_COOKIE["user"]) && empty($_SESSION["user"])){
            $_SESSION["user"] = $_COOKIE["user"];
        }
        header("Location: home.php");
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
                $_SESSION["emailFail"]=False;
            }
            if(isset($_SESSION["paswFail"]) && $_SESSION["paswFail"]){
                echo'<style>
                        input[name="psw"]{
                            background-color: rgba(255, 78, 113, 0.7);
                        }
                    </style>';
                $_SESSION["paswFail"]=False;
            }
        ?>
        <div class="container">
            <div class="left">
                <img src="images/logo.png" alt="logo" id="logo">
                <div class="log">
                    <h1>Accedi</h1>
                    <span>Accedi con i dati che hai inserito durante la registrazione.</span>
                    <form action="access/loginDB.php" method="POST">
                        <label for="email"><b>Email</b></label>
                        <input type="text" placeholder="nome@esempio.com" name="email" 
                            <?php
                                if(isset($_SESSION["userLogin"])){
                                    echo "value='".$_SESSION["userLogin"]."'";
                                }
                            ?> 
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>

                        <label for="psw"><b>Password</b></label>
                        <input type="password" placeholder="min. 8 caratteri" name="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Deve contenere almeno un numero e una lettera maiuscola e minuscola e almeno 8 o più caratteri" required>
                        <label><input type="checkbox" id="remember" value="1" name="remember"><a id="labelCheck">Ricordami su questo dispositivo</a></label>
                        <button type="submit" name="login" class="logbtn">Accedi</button>
                    </form>
                    <div class="pswDiv">
                        <a href="ridersLogin.php" class="Link">Area rider</a>
                    </div>
                </div>  
                <div class="bottom">
                        <span>Non hai un account? <a href="signUp.php" class="Link">Registrati</a></span>
                </div>
            </div>
            <div class="right">

            </div>
        </div>
    </body>
</html>