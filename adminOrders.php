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
    $conn->query("USE my_ilpescaggio");
    $bag = $conn->query('SELECT SUM(quantity) FROM cart WHERE idUser="'.$_SESSION["user"].'" AND cart.catering = 0;');
    $bag = mysqli_fetch_assoc($bag); 
    if (!isset($_SESSION["typefood"])){
        $_SESSION["typefood"] = "pizza";
    }
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
        <title>GESTIONE ORDINI</title>
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
                <a href="admin.php" class="navLink" style="color: #4e60ff">Admin</a>
                <a href="catering.php" class="navLink">Catering</a>
                <a href="orders.php" class="navLink">Ordini</a>
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
                $allOrders = $conn->query('SELECT * FROM forder ORDER BY dateAndTimePay DESC;');
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
                                    }
                                    echo '" alt="icon" style="margin-right:10px;">
                                    <h3 class="itemName">Ordine di: <span style="color:#F84F31">'.htmlspecialchars($rowBig['firstName'])." ".htmlspecialchars($rowBig['surname']).'</span> alle ore: <span style="color:#F84F31">'.htmlspecialchars($rowBig['dateAndTimePay']).'</span></h3>
                                </div>
                            </label>
                            <div class="collapsible-content">
                                <div class="content-inner">
                                    <div class="dishDiv">
                                        <div class="itemCard orderTime">
                                            <h3 class="itemName"> Email: <span style="color:#4E60FF">'.htmlspecialchars($rowBig['idUser']).'</span> </h3>
                                            <h3 class="itemName"> Telefono: <span style="color:#4E60FF">'.htmlspecialchars($rowBig['tel']).'</span> </h3>
                                        </div> ';
                        
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
                                            <h3 class="itemName">Totale: <span style="margin-right: 10px; font-weight: bold;color: red">'.$totalPrice.'€</span></h3>
                                        </div>
                                    </div>';
                        if($rowBig['orderStatus'] == 1){
                            echo'       <form action="access/adminOrdersDB.php" method="POST">
                                            <input type="hidden" name="idOrder" value="'.$rowBig['id'].'">
                                            <div class="itemCard">
                                                <div class="itemRight">
                                                    <button type="submit" name="confirm" value="False" class="logbtn delBtn">Rifiuta</button>
                                                </div>
                                                <div class="itemLeft">
                                                    <button type="submit" name="confirm" value="True" class="logbtn">Conferma</button>
                                                </div>   
                                            </div>
                                        </form>';
                        }
                    }
                    else{
                        $totalPrice += 2;
                        echo '      
                                    <div class="itemCard">
                                        <div class="itemRight">
                                            <h3 class="itemName">Consegna</h3>
                                        </div>
                                        <div class="itemLeft">
                                            <span style="margin-right: 10px; font-weight: bold;">2€</span>
                                        </div>
                                    </div> 
                                    <div class="itemCard">
                                        <div class="itemRight">
                                        <h3 class="itemName"> Indirizzo di spedizione: <span style="font-weight: bold; color: green">'.htmlspecialchars($rowBig['via']).' '.htmlspecialchars($rowBig['civ']).', '.htmlspecialchars($rowBig['cap']).'</span></h3>
                                        </div>
                                        <div class="itemLeft">
                                            <h3 class="itemName">totale: <span style="margin-right: 10px; font-weight: bold;color: red">'.$totalPrice.'€</span></h3>
                                        </div>
                                    </div>';
                        if($rowBig['orderStatus'] == 1){
                            
                            echo'       <form action="access/adminOrdersDB.php" method="POST">
                                            <input type="hidden" name="idOrder" value="'.$rowBig['id'].'">
                                            <div class="itemCard">
                                                <div class="itemRight">
                                                    <button type="submit" name="confirm" value="False" class="logbtn delBtn">Rifiuta</button>
                                                </div>
                                                <div class="itemLeft">
                                                    <button type="submit" name="confirm" value="True" class="logbtn">Conferma</button>
                                                </div>   
                                            </div>
                                        </form>';
                        }
                        if($rowBig['orderStatus'] == 2){
                            echo'       <form action="access/adminOrdersDB.php" method="POST">
                                            <input type="hidden" name="idOrder" value="'.$rowBig['id'].'">
                                            <div class="itemCard">
                                                <div class="itemRight">
                                                    <h3 class="itemName">Rider: 
                                                        <select name="rider" id="rider">';

                            $riders = $conn->query('SELECT * FROM rider WHERE available = 1;');   
                            while($rider = $riders->fetch_assoc()){   
                                echo' <option value="'.htmlspecialchars($rider['email']).'">'.htmlspecialchars($rider['riderName']).' '.htmlspecialchars($rider['riderSurname']).'</option> ';
                            }
                            echo'                       </select>
                                                    </h3>
                                                </div>
                                                <div class="itemLeft">
                                                    <button type="submit" name="send" value="True" class="logbtn">Spedisci</button>
                                                </div>
                                            </div>
                                        </form>';
                        }
                        if($rowBig['orderStatus'] == 3){
                            $rider = $conn->query('SELECT * FROM forder, rider WHERE id = '.$rowBig['id'].' and email = idRider;');  
                            $rider = mysqli_fetch_assoc($rider); 
                            echo'       <div class="itemCard">
                                                <div class="itemRight">
                                                    <h3 class="itemName">Rider: <span style="font-weight: bold; color: green">'.htmlspecialchars($rider['riderName']).' '.htmlspecialchars($rider['riderSurname']).'</span></h3>
                                                </div>
                                                <div class="itemLeft">
                                                    <h3 class="itemName"><span style="margin-right: 10px; font-weight: bold;color: red">In consegna</span></h3>
                                                </div>   
                                            </div>
                                        ';
                        }
                        if($rowBig['orderStatus'] == 4){
                            $rider = $conn->query('SELECT * FROM forder, rider WHERE id = '.$rowBig['id'].' and email = idRider;');  
                            $rider = mysqli_fetch_assoc($rider); 
                            echo'       <div class="itemCard">
                                                <div class="itemRight">
                                                    <h3 class="itemName">Rider: <span style="font-weight: bold; color: green">'.htmlspecialchars($rider['riderName']).' '.htmlspecialchars($rider['riderSurname']).'</span></h3>
                                                </div>
                                                <div class="itemLeft">
                                                    <h3 class="itemName">Consegnato alle: <span style="font-weight: bold; color: green">'.htmlspecialchars($rider['dateAndTimeDelivered']).'</span></h3>
                                                </div>   
                                            </div>
                                        ';
                        }
                    }
                    echo '      </div>
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