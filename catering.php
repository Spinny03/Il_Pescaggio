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
    $bag = $conn->query('SELECT SUM(quantity) FROM cart WHERE idUser="'.$_SESSION["user"].'" AND cart.catering = 0;');
    $bag = mysqli_fetch_assoc($bag); 
    $data = $conn->query('SELECT * FROM username WHERE email ="'.$_SESSION["user"].'";');
    $data = mysqli_fetch_assoc($data); 

    $totalPrice = 0;
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
        <link rel="stylesheet" href="css/cateringStyles.css">

        <script src="js/navbarRes.js" defer></script>
        <script src="js/footer.js" defer></script>
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>CATERING</title>
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
                <a href="catering.php" class="navLink" style="color: #4e60ff">Catering</a>
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
        <div class="container">
            <div class="left">
                <h2>Piatti disponibili</h2>
                <div class="wrap-collabsible">
                    <input id="collapsible2" class="toggle" type="checkbox">
                    <label for="collapsible2" class="lbl-toggle">
                        <div class="titleDiv">
                            <img width="40px" height="40px" src="images/foodType/pizza.png" alt="pizza">
                            <span>Pizza</span>
                        </div>
                    </label>
                    <div class="collapsible-content">
                        <div class="content-inner">
                            <div class="dishDiv">
                                <?php
                                    $dishs = $conn->query('SELECT * FROM dish WHERE dishType="pizza" AND visible=1;');
                                    
                                    while($row = $dishs->fetch_assoc()){

                                        $cart = $conn->query('SELECT quantity FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND  dishName="'.$row["dishName"].'" AND dish.id = cart.idDish AND cart.catering = 1;');
                                        $cart = mysqli_fetch_assoc($cart);
                                        $inCart = "";
                                        if(!empty($cart["quantity"])){
                                            $inCart = "cart";
                                        }

                                        echo '  <div class="itemCard "'.$inCart.'>
                                                    <div class="itemRight">
                                                        <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                                                    </div>
                                                    <div class="itemLeft">
                                                        <span style="margin-right: 10px; font-weight: bold;">'.htmlspecialchars($row['dishCost']).'€</span>
                                                        <form id="addishForm" action="access/cateringDB.php" method="POST">
                                                            <input type="hidden" name="catering" value="'.$inCart.'">
                                                            <input type="hidden" name="dish" value="'.$row["id"].'">
                                                            <input type ="checkbox" onChange="this.form.submit()"';
                                                            if($inCart == "cart"){ echo 'checked'; $totalPrice =  $totalPrice + intval($row['dishCost']);}
                                        echo '          ></form>
                                                    </div>
                                                </div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wrap-collabsible">
                    <input id="collapsible3" class="toggle" type="checkbox">
                    <label for="collapsible3" class="lbl-toggle">
                        <div class="titleDiv">
                            <img width="40px" height="40px" src="images/foodType/burger.png" alt="burger">
                            <span>burger</span>
                        </div>
                    </label>
                    <div class="collapsible-content">
                        <div class="content-inner">
                            <div class="dishDiv">
                                <?php
                                    $dishs = $conn->query('SELECT * FROM dish WHERE dishType="burger" AND visible=1;');
                                    
                                    while($row = $dishs->fetch_assoc()){

                                        $cart = $conn->query('SELECT quantity FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND  dishName="'.$row["dishName"].'" AND dish.id = cart.idDish AND cart.catering = 1;');
                                        $cart = mysqli_fetch_assoc($cart);
                                        $inCart = "";
                                        if(!empty($cart["quantity"])){
                                            $inCart = "cart";
                                        }

                                        echo '  <div class="itemCard "'.$inCart.'>
                                                    <div class="itemRight">
                                                        <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                                                    </div>
                                                    <div class="itemLeft">
                                                        <span style="margin-right: 10px; font-weight: bold;">'.htmlspecialchars($row['dishCost']).'€</span>
                                                        <form id="addishForm" action="access/cateringDB.php" method="POST">
                                                            <input type="hidden" name="catering" value="'.$inCart.'">
                                                            <input type="hidden" name="dish" value="'.$row["id"].'">
                                                            <input type ="checkbox" onChange="this.form.submit()"';
                                                            if($inCart == "cart"){ echo 'checked'; $totalPrice =  $totalPrice + intval($row['dishCost']);}
                                        echo '          ></form>
                                                    </div>
                                                </div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wrap-collabsible">
                    <input id="collapsible4" class="toggle" type="checkbox">
                    <label for="collapsible4" class="lbl-toggle">
                        <div class="titleDiv">
                            <img width="40px" height="40px" src="images/foodType/meat.png" alt="meat">
                            <span>carne</span>
                        </div>
                    </label>
                    <div class="collapsible-content">
                        <div class="content-inner">
                            <div class="dishDiv">
                                <?php
                                    $dishs = $conn->query('SELECT * FROM dish WHERE dishType="meat" AND visible=1;');
                                    
                                    while($row = $dishs->fetch_assoc()){

                                        $cart = $conn->query('SELECT quantity FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND  dishName="'.$row["dishName"].'" AND dish.id = cart.idDish AND cart.catering = 1;');
                                        $cart = mysqli_fetch_assoc($cart);
                                        $inCart = "";
                                        if(!empty($cart["quantity"])){
                                            $inCart = "cart";
                                        }

                                        echo '  <div class="itemCard "'.$inCart.'>
                                                    <div class="itemRight">
                                                        <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                                                    </div>
                                                    <div class="itemLeft">
                                                        <span style="margin-right: 10px; font-weight: bold;">'.htmlspecialchars($row['dishCost']).'€</span>
                                                        <form id="addishForm" action="access/cateringDB.php" method="POST">
                                                            <input type="hidden" name="catering" value="'.$inCart.'">
                                                            <input type="hidden" name="dish" value="'.$row["id"].'">
                                                            <input type ="checkbox" onChange="this.form.submit()"';
                                                            if($inCart == "cart"){ echo 'checked'; $totalPrice =  $totalPrice + intval($row['dishCost']);}
                                        echo '          ></form>
                                                    </div>
                                                </div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wrap-collabsible">
                    <input id="collapsible5" class="toggle" type="checkbox">
                    <label for="collapsible5" class="lbl-toggle">
                        <div class="titleDiv">
                            <img width="40px" height="40px" src="images/foodType/fish.png" alt="fish">
                            <span>pesce</span>
                        </div>
                    </label>
                    <div class="collapsible-content">
                        <div class="content-inner">
                            <div class="dishDiv">
                                <?php
                                    $dishs = $conn->query('SELECT * FROM dish WHERE dishType="fish" AND visible=1;');
                                    
                                    while($row = $dishs->fetch_assoc()){

                                        $cart = $conn->query('SELECT quantity FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND  dishName="'.$row["dishName"].'" AND dish.id = cart.idDish AND cart.catering = 1;');
                                        $cart = mysqli_fetch_assoc($cart);
                                        $inCart = "";
                                        if(!empty($cart["quantity"])){
                                            $inCart = "cart";
                                        }

                                        echo '  <div class="itemCard "'.$inCart.'>
                                                    <div class="itemRight">
                                                        <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                                                    </div>
                                                    <div class="itemLeft">
                                                        <span style="margin-right: 10px; font-weight: bold;">'.htmlspecialchars($row['dishCost']).'€</span>
                                                        <form id="addishForm" action="access/cateringDB.php" method="POST">
                                                            <input type="hidden" name="catering" value="'.$inCart.'">
                                                            <input type="hidden" name="dish" value="'.$row["id"].'">
                                                            <input type ="checkbox" onChange="this.form.submit()"';
                                                            if($inCart == "cart"){ echo 'checked'; $totalPrice =  $totalPrice + intval($row['dishCost']);}
                                        echo '          ></form>
                                                    </div>
                                                </div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wrap-collabsible">
                    <input id="collapsible6" class="toggle" type="checkbox">
                    <label for="collapsible6" class="lbl-toggle">
                        <div class="titleDiv">
                            <img width="40px" height="40px" src="images/foodType/drink.png" alt="drink">
                            <span>Bevande</span>
                        </div>
                    </label>
                    <div class="collapsible-content">
                        <div class="content-inner">
                            <div class="dishDiv">
                                <?php
                                    $dishs = $conn->query('SELECT * FROM dish WHERE dishType="drink" AND visible=1;');
                                    
                                    while($row = $dishs->fetch_assoc()){

                                        $cart = $conn->query('SELECT quantity FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND  dishName="'.$row["dishName"].'" AND dish.id = cart.idDish AND cart.catering = 1;');
                                        $cart = mysqli_fetch_assoc($cart);
                                        $inCart = "";
                                        if(!empty($cart["quantity"])){
                                            $inCart = "cart";
                                        }

                                        echo '  <div class="itemCard "'.$inCart.'>
                                                    <div class="itemRight">
                                                        <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                                                    </div>
                                                    <div class="itemLeft">
                                                        <span style="margin-right: 10px; font-weight: bold;">'.htmlspecialchars($row['dishCost']).'€</span>
                                                        <form id="addishForm" action="access/cateringDB.php" method="POST">
                                                            <input type="hidden" name="catering" value="'.$inCart.'">
                                                            <input type="hidden" name="dish" value="'.$row["id"].'">
                                                            <input type ="checkbox" onChange="this.form.submit()"';
                                                            if($inCart == "cart"){ echo 'checked'; $totalPrice =  $totalPrice + intval($row['dishCost']);}
                                        echo '          ></form>
                                                    </div>
                                                </div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wrap-collabsible">
                    <input id="collapsible7" class="toggle" type="checkbox">
                    <label for="collapsible7" class="lbl-toggle">
                        <div class="titleDiv">
                            <img width="40px" height="40px" src="images/foodType/desserts.png" alt="desserts">
                            <span>dolci</span>
                        </div>
                    </label>
                    <div class="collapsible-content">
                        <div class="content-inner">
                            <div class="dishDiv">
                                <?php
                                    $dishs = $conn->query('SELECT * FROM dish WHERE dishType="desserts" AND visible=1;');
                                    
                                    while($row = $dishs->fetch_assoc()){

                                        $cart = $conn->query('SELECT quantity FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND  dishName="'.$row["dishName"].'" AND dish.id = cart.idDish AND cart.catering = 1;');
                                        $cart = mysqli_fetch_assoc($cart);
                                        $inCart = "";
                                        if(!empty($cart["quantity"])){
                                            $inCart = "cart";
                                        }

                                        echo '  <div class="itemCard "'.$inCart.'>
                                                    <div class="itemRight">
                                                        <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                                                    </div>
                                                    <div class="itemLeft">
                                                        <span style="margin-right: 10px; font-weight: bold;">'.htmlspecialchars($row['dishCost']).'€</span>
                                                        <form id="addishForm" action="access/cateringDB.php" method="POST">
                                                            <input type="hidden" name="catering" value="'.$inCart.'">
                                                            <input type="hidden" name="dish" value="'.$row["id"].'">
                                                            <input type ="checkbox" onChange="this.form.submit()"';
                                                            if($inCart == "cart"){ echo 'checked'; $totalPrice =  $totalPrice + intval($row['dishCost']);}
                                        echo '          ></form>
                                                    </div>
                                                </div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="right">
                    <h2>Conferma dati</h2>
                    <form action="access/sendOrderDB.php" method="POST">
                        <input type="hidden" name="fromCatering" value=1>
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

                        <div class="data" id="p50">
                            <label for="day"><b>Data</b></label>
                            <input type="date" name="day" min="<?php echo date("Y-m-d"); ?>" required>
                        </div>

                        <div class="data" id="p50">
                            <label for="hours"><b>Ora</b></label>
                            <input type="time" placeholder="" name="hours" 
                            required>
                        </div>

                        <div class="data" id="p100">
                            <label for="reservations"><b>Prenotazioni</b></label>
                            <input type="text" placeholder="69" name="reservations"  
                            required>
                        </div>

                        <div class="data" id="p100">
                            <label for="nCard"><b>Richieste particolari</b></label>
                            <textarea name="notes" rows="6"></textarea>
                        </div> 

                        <div class="priceRecap data" id="p100">
                            <div class="innerPrice foodPrice">
                                <h3>Cibo:</h3>
                                <h4><?php echo $totalPrice; ?>€</h4>
                            </div>

                            <div class="innerPrice totalPrice">
                                <h3>Totale:</h3>
                                <h4><?php echo $totalPrice; ?>€</h4>
                            </div>
                        </div>

                        <button type="submit" name="cancelOrder" class="delBtn" form="exitForm">Annulla</button> 
                        <button type="submit" name="confirmOdrer" class="confBtn">Conferma ordine</button>          

                    </form>

                    <form action="home.php" id="exitForm"></form>

            </div>
        </div>
        <?php $conn->close(); ?>
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
</html>