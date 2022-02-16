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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="viewport" content="width=device-width" />

        <link rel="stylesheet" href="css/navBarStyles.css">
        <link rel="stylesheet" href="css/formStyles.css">
        <link rel="stylesheet" href="css/cartStyles.css">
        <link rel="stylesheet" href="css/scrollBarStyles.css">
        <link rel="stylesheet" href="css/cateringStyles.css">

        <script src="js/navbarRes.js" defer></script>
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>CARRELLO</title>
    </head>
    <body>
        <?php 
            $photo = $conn->query('SELECT photoLink FROM username WHERE email="'.$_SESSION["user"].'";');
            $photo = mysqli_fetch_assoc($photo); 
            if(!empty($photo["photoLink"])){
                echo'<style>
                        a[id="profileBtn"]{
                            background: url("images/userPhoto/'.$photo["photoLink"].'");
                        }
                    </style>';
            }
            else{
                echo'<style>
                        a[id="profileBtn"]{
                            background: url("images/icons/profile.png");
                        }
                    </style>';
            }
        ?>
        <nav class="navBar">
            <a href="home.php">
                <img src="images/smallLogo.png" alt="logo" id="logo">
            </a>
            <ul class="navItems" data-visible="false">
                <a href="home.php" class="navLink">Delivery</a>
                <a href="#" class="navLink">Catering</a>
                <a href="#" class="navLink">Ordini</a>
            </ul>
            
            <a href="cart.php" class="navBtn" id="shoppingCard" style="background-color: #4e60ff;">
                <img src="images/icons/whiteBag.svg" alt="logo" id="shoppingSVG"> 
            </a>
            <a href="profile.php" class="navBtn" id="profileBtn">

            </a>
            <button class="navBtn" id="respBtn">
                <img src="images/icons/respBtn.svg" alt="menu" id="respImg">
            </button>
        </nav>
        <div class="container">
            <div class="left">
                <h2>cicicci</h2>
            <div class="wrap-collabsible">
                <input id="collapsible2" class="toggle" type="checkbox" checked="">
                <label for="collapsible2" class="lbl-toggle">Even More Info</label>
                <div class="collapsible-content">
                    <div class="content-inner">
                        <p> Bacon ipsum dolor amet pork belly capicola ground round brisket. Shank pork belly cupim fatback. Flank porchetta chicken spare ribs. Ham prosciutto pork belly turkey beef picanha strip steak swine alcatra.</p>
                        <p> Bacon ipsum dolor amet pork belly capicola ground round brisket. Shank pork belly cupim fatback. Flank porchetta chicken spare ribs. Ham prosciutto pork belly turkey beef picanha strip steak swine alcatra.</p>
                    </div>
                </div>
            </div>

            </div>

            <div class="right">
                    <h2>Conferma dati</h2>
                    <form action="access/profileDB.php" method="POST">
                        <div class="data" id="p50">
                            <label for="name"><b>Nome</b></label>
                            <input type="text" placeholder="Mario" name="name"
                                <?php
                                    if(isset($data["firstName"])){
                                        echo "value='".$data["firstName"]."'";
                                    }
                                ?> 
                            required>
                        </div>

                        <div class="data" id="p50">
                            <label for="surname"><b>Cognome</b></label>
                            <input type="text" placeholder="Rossi" name="surname"
                                <?php
                                    if(isset($data["surname"])){
                                        echo "value='".$data["surname"]."'";
                                    }
                                ?> 
                            required>
                        </div>

                        <div class="data" id="p100">
                            <label for="via"><b>Via</b></label>
                            <input type="text" placeholder="Via Sestri" name="via" 
                                <?php
                                    if(isset($data["via"])){
                                        echo "value='".$data["via"]."'";
                                    }
                                ?> 
                            required>
                        </div>

                        <div class="data" id="p50">
                            <label for="civ"><b>Civ</b></label>
                            <input type="text" placeholder="17/11" name="civ" 
                                <?php
                                    if(isset($data["civ"])){
                                        echo "value='".$data["civ"]."'";
                                    }
                                ?> 
                            required>
                        </div>

                        <div class="data" id="p50">
                            <label for="cap"><b>Cap</b></label>
                            <input type="text" placeholder="16153" name="cap" 
                                <?php
                                    if(isset($data["cap"])){
                                        echo "value='".$data["cap"]."'";
                                    }
                                ?> 
                            required>
                        </div>

                        <div class="data" id="p100">
                            <label for="nCard"><b>Carta di credito</b></label>
                            <input type="text" placeholder="0123 4567 7890" name="nCard" 
                                <?php
                                    if(isset($data["nCard"])){
                                        echo "value='".$data["nCard"]."'";
                                    }
                                ?> 
                            required>
                        </div>

                        <div class="data" id="p70">
                            <label for="nCard"><b>Data di scadenza</b></label>
                            <input type="text" placeholder="12/26" name="expireCard" required>
                        </div>

                        <div class="data" id="p30">
                            <label for="nCard"><b>CVV</b></label>
                            <input type="text" placeholder="123" name="expireCard" required>
                        </div>  

                        <div class="priceRecap data" id="p100">
                            <div class="innerPrice foodPrice">
                                <h3>Cibo:</h3>
                                <h4><?php echo $totalPrice; ?>€</h4>
                            </div>
                                
                            <div class="innerPrice deliveryPrice">
                                <h3>Consegna:</h3>
                                <h4>10€</h4>
                            </div>

                            <div class="innerPrice totalPrice">
                                <h3>Totale:</h3>
                                <h4><?php echo $totalPrice+10; ?>€</h4>
                            </div>
                        </div>

                        <button type="submit" name="cancelOrder" class="delBtn" form="exitForm">Annulla</button> 
                        <button type="submit" name="confirmOdrer" class="confBtn">Conferma oridne</button>          

                    </form>

                    <form action="home.php" id="exitForm"></form>

            </div>
        </div>
    </body>
</html>