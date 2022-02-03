<?php 
    session_start();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="css/styles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>Il Pescaggio</title>
    </head>
    <body>
        <?php 
            if(isset($_SESSION["exist"]) && $_SESSION["exist"]){
                echo'<style>
                        input[name="email"]{
                            background-color: rgba(200,0,0,0.4);
                        }
                    </style>';
                $_SESSION["exist"]=False;
            }
            if(isset($_SESSION["check"]) && $_SESSION["check"]){
                echo'<style>
                        input[name="Cpsw"]{
                            background-color: rgba(200,0,0,0.4);
                        }
                    </style>';
                $_SESSION["check"]=False;
            }
        ?>
        <div class="container">
            <div class="left">
                <img src="images/logo.png" alt="logo" id="logo">
                <div class="log">
                    <h1>Registrati</h1>
                    <span>Inserire i tuoi dati personali per creare un nuovo account</span>
                    <form action="access/registerDB.php" method="POST">
                        
                        <label for="email"><b>Email</b></label>
                        <input type="text" placeholder="nome@esempio.com" name="email" required >
                        
                        <label for="psw"><b>Password</b></label>
                        <input type="password" placeholder="min. 8 caratteri" name="psw" minlength="8" required>
                        <label for="Cpsw"><b>Conferma password</b></label>
                        <input type="password" placeholder="inserisci la stessa password" name="Cpsw" minlength="8" required>
                        <button type="submit" name="register" class="logbtn">Registrati</button>
                    </form>
                </div>
                <div class="bottom">
                        <span>Sei già registrato? <a href="index.php" class="Link">Accedi</a></span>
                </div>
            </div>
            <div class="right">

            </div>
        </div>
    </body>
</html>