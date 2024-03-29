<?php 
    session_start(); 
    if(empty($_SESSION["user"]) && empty($_COOKIE["user"])){
        header("Location: index.php");
        exit();
    }
    if(empty($_SESSION["user"])){
        if(isset($_COOKIE["user"])){
            $_SESSION["user"] = $_COOKIE["user"];
        }
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
    if(!isset($_SESSION["bigNews"]) || $_SESSION["bigNews"] != "news"){
        $bigNews = $conn->query('SELECT notice FROM username WHERE email="'.$_SESSION["user"].'";');
        $bigNews = mysqli_fetch_assoc($bigNews); 
        if($bigNews["notice"] == 1){
            $_SESSION["bigNews"] = "news";
        }
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
        <link rel="stylesheet" href="css/cartStyles.css">
        <link rel="stylesheet" href="css/scrollBarStyles.css">
        <script src="js/navbarRes.js" defer></script>
        <script src="js/footer.js" defer></script>
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>CARRELLO</title>
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
                <?php 
                    if($_SESSION["user"]=="admin@ilpescaggio.it"){
                        echo '<a href="admin.php" class="navLink">Admin</a>';
                    }
                ?>
                <a href="catering.php" class="navLink">Catering</a>
                <a href="orders.php" class="navLink">Ordini                     
                        <?php 
                            if(isset($_SESSION["bigNews"]) && $_SESSION["bigNews"] == "news"){
                                echo '<span id="bigNews"></span>';
                            }
                        ?>
                    </a>
            </ul>
            
            <a href="cart.php" class="navBtn" id="shoppingCard" style="background-color: #4e60ff;">
                <img src="images/icons/whiteBag.svg" alt="logo" id="shoppingSVG"> 
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
        <div class="container">
            <div class="left">
                <h2>Articoli carrello</h2>
                <?php 
                    $sql = 'SELECT dish.dishName, quantity, dishCost, dish.id FROM cart, dish  WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish AND cart.catering = 0  ORDER BY lastChange DESC;';
                    $result = $conn->query($sql); 
                    $totalPrice = 0;
                    $clear = True;
                    while($row = $result->fetch_assoc()){ 
                        $clear = False;  
                        echo '  <div class="itemCard">
                                    <div class="itemRight">
                                        <span class="itemNumber">'.htmlspecialchars($row['quantity']).'</span>
                                        <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                                    </div>
                                    <div class="itemLeft">
                                    <span style="margin-right: 10px; font-weight: bold;">'.htmlspecialchars($row['dishCost']).'€</span>
                                    <form action="access/cartDB.php" method="POST">
                                        <input type="hidden" name="cameFromCart" value="1">
                                        <button type="submit" class="itemNumber formBtn" name="del" value="'.$row["id"].'" style="background-color: red;">
                                            x
                                        </button>
                                        <button type="submit" class="itemNumber formBtn" name="less" value="'.$row["id"].'" style="background-color: #ffd300;">
                                            -
                                        </button>
                                        <button type="submit" class="itemNumber formBtn" name="add" value="'.$row["id"].'" style="background-color: green;">
                                            +
                                        </button>
                                    </form>
                                    </div>
                                         
                                </div>';
                        $totalPrice =  $totalPrice + intval(htmlspecialchars($row['dishCost'])*intval(htmlspecialchars($row['quantity'])));
                    }
                    if($clear){
                        echo '  <div class="itemCard" style="color:#ff0000;">
                                    <div class="itemRight">
                                        <h3 class="itemName" style="color:#ff0000;">Carrello vuoto</h3>
                                    </div>                       
                                </div>';
                    }

                ?>

            </div>

            <div class="right">
                    <h2>Conferma dati</h2>
                    <form action="access/sendOrderDB.php" method="POST">
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
                            <label for="tel"><b>Telefono</b></label>
                            <input type="text" placeholder="+39 123-456-7890" name="tel" 
                                <?php
                                    if(isset($data["tel"])){
                                        echo "value='".$data["tel"]."'";
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
                                <h4>2€</h4>
                            </div>

                            <div class="innerPrice totalPrice">
                                <h3>Totale:</h3>
                                <h4><?php echo $totalPrice+2; ?>€</h4>
                            </div>
                        </div>

                        <button type="submit" name="cancelOrder" class="delBtn" form="exitForm">Annulla</button> 
                        <button type="submit" name="confirmOdrer" class="confBtn">Conferma ordine</button>          

                    </form>

                    <form action="home.php" id="exitForm"></form>

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
