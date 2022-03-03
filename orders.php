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
    $conn->query("USE my_ilpescaggio");
    $bag = $conn->query('SELECT SUM(quantity) FROM cart WHERE idUser="'.$_SESSION["user"].'" AND cart.catering = 0;');
    $bag = mysqli_fetch_assoc($bag); 
    if(isset($_SESSION["bigNews"]) && $_SESSION["bigNews"] == "news"){
        $_SESSION["bigNews"] = "";
        $conn->query('UPDATE username SET registrationDate = registrationDate, notice=0 WHERE email = "'.$_SESSION["user"].'"');
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        
        <link rel="stylesheet" href="css/navBarStyles.css">
        <link rel="stylesheet" href="css/formStyles.css">
        <link rel="stylesheet" href="css/scrollBarStyles.css">
        <link rel="stylesheet" href="css/cateringStyles.css">
        <link rel="stylesheet" href="css/cartStyles.css">
        <link rel="stylesheet" href="css/ordersStyles.css">

        <script src="js/navbarRes.js" defer></script>
        <script src="js/footer.js" defer></script>
        
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>ORDINI</title>
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
                <a href="home.php" class="navLink" >Delivery</a>
                <?php 
                    if($_SESSION["user"]=="admin@ilpescaggio.it"){
                        echo '<a href="admin.php" class="navLink">Admin</a>';
                    }
                ?>
                <a href="catering.php" class="navLink">Catering</a>
                <a href="orders.php" class="navLink" style="color: #4e60ff">Ordini</a>
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
            <?php 
                $allOrders = $conn->query('SELECT * FROM forder WHERE idUser="'.$_SESSION["user"].'" ORDER BY dateAndTimePay DESC;');
                $i=2;

                while($rowBig = $allOrders->fetch_assoc()){

                    echo'<div class="wrap-collabsible">
                            <input id="collapsible'.$i.'" class="toggle" type="checkbox">
                            <label for="collapsible'.$i.'" class="lbl-toggle">
                                <div class="titleDiv">
                                    <img width="40px" height="40px" src="images/icons/';
                                    if($rowBig['delivery'] == 1){
                                            echo 'delivery.svg';
                                        }
                                        else{
                                            echo 'catering.svg';
                                        }echo '" alt="icon" style="margin-right:10px;">
                                    <h3 class="itemName">Ordine del giorno: <span style="color:#F84F31">'.htmlspecialchars($rowBig['dateAndTimePay']).'</span> </h3>
                                </div>
                            </label>
                            <div class="collapsible-content">
                                <div class="content-inner">
                                    <div class="dishDiv">';
                    echo '              <div class="itemCard orderTime">     
                                            '; 
                                            if($rowBig['delivery'] == 0){
                                                echo'<h3 class="itemName"> Prenotato per il giorno: <span style="color:green"> '.htmlspecialchars($rowBig['dateAndTimeDelivered']).'</span></h3>';
                                            }
                                            if(isset($rowBig['dateAndTimeDelivered']) && $rowBig['delivery'] == 1 && $rowBig['orderStatus'] == 4){
                                                echo'<h3 class="itemName"> Consegnato il: <span style="color:green"> '.htmlspecialchars($rowBig['dateAndTimeDelivered']).'</span></h3>';
                                            }
                                            else{
                                                if($rowBig['orderStatus'] == -1){
                                                    echo '<h3 class="itemName"> Stato: <span style="color:#F84F31"> Non accettato</span></h3>';
                                                }
                                                if($rowBig['orderStatus'] == 1){
                                                    echo '<h3 class="itemName"> Stato: <span style="color:#F84F31"> In attesa</span></h3>';
                                                }
                                                if($rowBig['orderStatus'] == 2){
                                                    if($rowBig['delivery'] == 0){
                                                        echo '<h3 class="itemName"> Stato: <span style="color:#F84F31"> Accettato</span></h3>';
                                                    }
                                                    else{
                                                        echo '<h3 class="itemName"> Stato: <span style="color:#F84F31"> In preparazione</span></h3>';
                                                    }
                                                }
                                                if($rowBig['orderStatus'] == 3){
                                                    echo '<h3 class="itemName"> Stato: <span style="color:#F84F31"> In consegna</span></h3>';
                                                }
                                            } 
                    echo                '</div>';
                        
                    $dishs = $conn->query('SELECT * FROM orderedfood WHERE idOrder='.$rowBig["id"].';');

                    $totalPrice = 0;

                    while($row = $dishs->fetch_assoc()){

                        $cart = $conn->query('SELECT * FROM dish WHERE dish.id = '.$row["idDish"].';');
                        $cart = mysqli_fetch_assoc($cart);
                        $inCart = "";
                        if(!empty($cart["quantity"])){
                            $inCart = "cart";
                        }

                        echo '      <div class="itemCard "'.$inCart.'>
                                        <div class="itemRight">
                                            <span class="itemNumber">'.htmlspecialchars($row['quantity']).'</span>
                                            <h3 class="itemName">'.htmlspecialchars($cart['dishName']).'</h3>
                                        </div>
                                        <div class="itemLeft">
                                            <span style="margin-right: 10px; font-weight: bold;">'.htmlspecialchars($cart['dishCost']).'€</span>
                                        </div>
                                    </div>';
                                    $totalPrice += $cart['dishCost'] * $row['quantity'];
                    }

                    if($rowBig["delivery"] == 0){
                        $totalPrice = $totalPrice * $rowBig["reservations"];
                        echo '      
                                    <div class="itemCard">
                                            <h3 style="overflow-wrap: anywhere;"class="itemName">Note:
                                            <span style="margin-right: 10px; font-weight: bold;">'.$rowBig['note'].'</span></h3>
                                    </div> 
                                    <div class="itemCard">
                                        <div class="itemRight">
                                            <h3 class="itemName"> Numero prenotazioni: <span style="font-weight: bold; color: green">'.htmlspecialchars($rowBig['reservations']).'</span></h3>
                                        </div>
                                        <div class="itemLeft">
                                            <h3 class="itemName">totale: <span style="margin-right: 10px; font-weight: bold;color: red">'.$totalPrice.'€</span></h3>
                                        </div>
                                    </div>';
                    }
                    else{
                        $totalPrice += 2;
                        echo '      <div class="itemCard">
                                        <div class="itemRight">
                                            <h3 class="itemName">Consegna</h3>
                                        </div>
                                        <div class="itemLeft">
                                            <span style="margin-right: 10px; font-weight: bold;">2€</span>
                                        </div>
                                    </div>      
                                    <div class="itemCard">
                                        <div class="itemRight">
                                            <h3 class="itemName"> indirizzo di spedizione: <span style="font-weight: bold; color: green">'.htmlspecialchars($rowBig['via']).' '.htmlspecialchars($rowBig['civ']).', '.htmlspecialchars($rowBig['cap']).'</span></h3>
                                        </div>
                                        <div class="itemLeft">
                                            <h3 class="itemName">totale: <span style="margin-right: 10px; font-weight: bold;color: red">'.$totalPrice.'€</span></h3>
                                        </div>
                                    </div>';
                    }

                    echo '    
                                </div>
                            </div>
                        </div>
                    </div>';
                    $i++;
                }
            ?>    
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