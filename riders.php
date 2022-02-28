<?php 
    session_start(); 
    if((empty($_SESSION["user"]) && empty($_COOKIE["user"])) || ($_SESSION["user"]!="admin@ilpescaggio.it" && $_COOKIE["user"]!="admin@ilpescaggio.it")){
        header("Location: home.php");
        exit();
    }
    if(empty($_SESSION["user"])){
        if(isset($_COOKIE["user"]) && $_COOKIE["user"]=="admin@ilpescaggio.it"){
            $_SESSION["user"] = $_COOKIE["user"];
        }
    }
    
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");
    $bag = $conn->query('SELECT SUM(quantity) FROM cart WHERE idUser="'.$_SESSION["user"].'" AND cart.catering = 0;');
    $bag = mysqli_fetch_assoc($bag);
    
    if(empty($dataPL)){
        $link = "images/icons/dish.png";
    }
    else{
        $link = "images/photoDishes/".$dataPL;
    } 
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="viewport" content="width=device-width" />

        <link rel="stylesheet" href="css/navBarStyles.css">
        <link rel="stylesheet" href="css/formStyles.css">
        <link rel="stylesheet" href="css/dishsStyles.css">
        <link rel="stylesheet" href="css/scrollBarStyles.css">
        <script src="js/navbarRes.js" defer></script>
        <script src="js/footer.js" defer></script>
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>GESTIONE RIDERS</title>
    </head>
    <body onload="footerHeight()" onresize="footerHeight()">
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
                <a href="admin.php" class="navLink" style="color: #4e60ff">Admin</a>
                <a href="catering.php" class="navLink">Catering</a>
                <a href="orders.php" class="navLink">Ordini                     
                        <?php 
                            if(isset($_SESSION["bigNews"]) && $_SESSION["bigNews"] == "news"){
                                echo '<span id="bigNews"></span>';
                            }
                        ?>
                    </a>
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
                    <a>
                        <img src="images/icons/respBtn.svg" alt="menu" id="respImg">
                        <?php 
                            if(isset($_SESSION["bigNews"]) && $_SESSION["bigNews"] == "news"){
                                echo '<span id="bigNewsSm"></span>';
                            }
                        ?>
                    </a>
                </button>
        </nav>
        <div class="container"  style="height: auto; min-height: 1000px;">
            <div class="left">
                <h2>Riders</h2>
                <?php 
                    $sql = 'SELECT * FROM rider ORDER BY riderName DESC;';
                    $result = $conn->query($sql); 
                    $totalPrice = 0;
                    while($row = $result->fetch_assoc()){   
                        echo '  
                            <div class="itemCard">
                                <div class="itemRight">
                                    <span class="itemNumber" style="background-color: #f3f4ff;"><img width="100%" height="100%" src="images/icons/profile.png" alt="pizza"></span>
                                    <h3 class="itemName">'.htmlspecialchars($row['riderName']).' '.htmlspecialchars($row['riderSurname']).'</h3>
                                </div>
                                <div class="itemLeft">
                                    <form action="access/ridersDB.php" method="POST">
                                        <button type="submit" class="itemNumber formBtn" name="del" value="'.$row["email"].'" style="background-color: white; margin-left:10px;">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </div>
                        ';
                    }
                ?>

            </div>

            <div class="right">
                <h2>Aggiungi nuovo rider</h2>
                <div class="pSettings">
                    <form action="access/ridersDB.php" method="POST" >
                        <input type="hidden" name="idRider" value="new">
                        <div class="data" id="p50">
                            <label for="name"><b>Nome</b></label>
                            <input type="text" placeholder="Mario" name="name" required>
                        </div>

                        <div class="data" id="p50">
                            <label for="surname"><b>Cognome</b></label>
                            <input type="text" placeholder="Rossi" name="surname" required>
                        </div>
                        <div class="data" id="p50">
                            <label for="email"><b>Email</b></label>
                            <input type="text" placeholder="mario.rossi@esempio.it" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                        </div>
                        <div class="data" id="p50">
                            <label for="pasw"><b>Password</b></label>
                            <input type="text" placeholder="Mario" name="pasw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Deve contenere almeno un numero e una lettera maiuscola e minuscola e almeno 8 o più caratteri" minlength="8" required>
                        </div>
                        <button style="background-color: green;" type="submit" name="change" class="logbtn">Conferma nuovo rider</button>
                    </form>
                </div>
            </div>
        </div>
        <footer>

            <div class="footerDiv">
                <h2>Chi siamo</h2>
                <p>Il pescaggio è un ristornate di alta cucina specializzato nella cucina di mare, ex detentore di 3 stelle michelin, in questi ultimi hanni
                    ha deciso di avventurarsi anche verso una cucina meno sofisticata ma sempre di altissimo livello.
                </p>
            </div>
            <div class="footerDiv">
                <h2>Contatti</h2>
                <p>Telefono: +39 324 907 7196</p>
                <p>Mail: barscor75@gmail.com</p>
                <p>Partita iva: 86334519757</p>
                <h3>Sviluppato Da:</h3>
                <p>alessio.barletta.2003@calvino.edu.it</p>
                <p>filippo.spinella.2003@calvino.edu.it</p>
            </div>
            <div class="footerDiv">
                <h2>Link utili</h2>
                <p><a class="footerLink" href="home.php">Home</a></p>
                <p><a class="footerLink" href="catering.php">Catering</a></p>
                <p><a class="footerLink" href="orders.php">Ordini</a></p>
                <p><a class="footerLink" href="cart.php">Carrello</a></p>
                <p><a class="footerLink" href="profile.php">Profilo</a></p>
            </div>

        </footer>
    </body>
    <?php $conn->close(); ?>
</html>