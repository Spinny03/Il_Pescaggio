<?php 
    session_start(); 
    if(!isset($_SESSION["user"]) || empty($_SESSION["user"])){
        header("Location: index.php");
        exit();
    }
    $conn = new mysqli("localhost", "root", "");  
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");
    $bag = $conn->query('SELECT SUM(quantity) FROM cart WHERE idUser="'.$_SESSION["user"].'";');
    $bag = mysqli_fetch_assoc($bag); 
    $data = $conn->query('SELECT * FROM username WHERE email ="'.$_SESSION["user"].'";');
    $data = mysqli_fetch_assoc($data); 
    if(isset($_SESSION["emailFail"]) && $_SESSION["emailFail"]){
        echo'<style>
                input[name="email"]{
                    background-color: rgba(255, 78, 113, 0.7);
                }
            </style>';
        $_SESSION["emailFail"]=False;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="css/profile.css">
        <link rel="stylesheet" href="css/navBarStyles.css">
        <link rel="stylesheet" href="css/formStyles.css">
        
        <script src="js/navbarRes.js" defer></script>
        
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>HOME</title>
    </head>
    <body>
        <div class="conteinerP">
            <nav class="navBar">
                <a href="home.php">
                    <img src="images/smallLogo.png" alt="logo" id="logo">
                </a>
                <ul class="navItems" data-visible="false">
                    <a href="home.php" class="navLink" style="color: #4e60ff">Delivery</a>
                    <a href="#" class="navLink">Catering</a>
                    <a href="#" class="navLink">Ordini</a>
                </ul>
                
                <a href="cart.php" class="navBtn" id="shoppingCard">
                    <?php 
                        if(!empty($bag["SUM(quantity)"])){
                            echo '<span id="itemsNumber">'.$bag["SUM(quantity)"].'</span>';
                        }
                    ?>
                    <img src="images/icons/blueBag.svg" alt="logo" id="shoppingSVG"> 
                </a>
                <a href="profile.php" class="navBtn" id="profileBtn">

                </a>
                <button class="navBtn" id="respBtn">
                    <img src="images/icons/respBtn.svg" alt="menu" id="respImg">
                </button>
            </nav>
            <div class="title">
                <h2>Impostazioni Account</h2>
            </div>
            <div class="pSettings">
                <form action="access/profileDB.php" method="POST" >

                    <div class="data" id="p25">
                        <label for="name"><b>Nome</b></label>
                        <input type="text" placeholder="Mario" name="name"
                            <?php
                                if(isset($data["firstName"])){
                                    echo "value='".$data["firstName"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p25">
                        <label for="surname"><b>Cognome</b></label>
                        <input type="text" placeholder="Rossi" name="surname"
                            <?php
                                if(isset($data["surname"])){
                                    echo "value='".$data["surname"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="email"><b>Email</b></label>
                        <input type="text" placeholder="nome@esempio.com" name="email" 
                            <?php 
                                if(isset($data["email"])){
                                    echo "value='".$data["email"]."'";
                                }
                            ?>  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="tel"><b>Numero di telefono</b></label>
                        <input type="text" placeholder="123-456-7890" name="tel" 
                            <?php
                                if(isset($data["tel"])){
                                    echo "value='".$data["tel"]."'";
                                }
                            ?> pattern="[0-9]{10}"
                        >
                    </div>  
                    
                    <div class="data" id="p30">
                        <label for="via"><b>Via</b></label>
                        <input type="text" placeholder="Via Sestri" name="address1" 
                            <?php
                                if(isset($data["via"])){
                                    echo "value='".$data["via"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p10">
                        <label for="civ"><b>Civ</b></label>
                        <input type="text" placeholder="17/11" name="address2" 
                            <?php
                                if(isset($data["civ"])){
                                    echo "value='".$data["civ"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p10">
                        <label for="cap"><b>Cap</b></label>
                        <input type="text" placeholder="16154" name="postcode" 
                            <?php
                                if(isset($data["cap"])){
                                    echo "value='".$data["cap"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="nCard"><b>Carta di credito</b></label>
                        <input type="text" placeholder="0123 4567 8910" name="nCard" 
                            <?php
                                if(isset($data["nCard"])){
                                    echo "value='".$data["nCard"]."'";
                                }
                            ?> pattern="[0-9 ]{4} [0-9 ]{4} [0-9 ]{4}" title="Inserire nel formato 0123 4567 8910"
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="changPasw"><b>Cambia Password</b></label>
                        <input type="text" placeholder="Password1" name="changPasw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Deve contenere almeno un numero e una lettera maiuscola e minuscola e almeno 8 o più caratteri" minlength="8" >
                    </div>

                    <button type="submit" name="change" value="False"class="logbtn">Annulla modifiche</button>
                    <button type="submit" name="change" value="True"class="logbtn">Salva le modifiche</button>
                </form>
            </div>
        </div>  
    </body>
    <?php $conn->close(); ?>
</html>