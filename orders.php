<?php 
    session_start(); 
    if(empty($_SESSION["user"]) && empty($_COOKIE["user"])){
        header("Location: index.php");
        exit();
    }
    if(empty($_SESSION["user"]) || empty($_SESSION["user"])){
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
    if (!isset($_SESSION["typefood"])){
        $_SESSION["typefood"] = "pizza";
    }
    if(isset($_SESSION["bigNews"]) && $_SESSION["bigNews"] == "news"){
        $_SESSION["bigNews"] = "";
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
        
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>ORDINI</title>
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
                <a href="home.php" class="navLink" >Delivery</a>
                <a href="admin.php" class="navLink">Admin</a>
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
                <img src="images/icons/respBtn.svg" alt="menu" id="respImg">
            </button>
        </nav>
            
        <div class="container">
            <?php 
                $allOrders = $conn->query('SELECT id FROM forder WHERE idUser="'.$_SESSION["user"].'" ORDER BY dateAndTimePay DESC;');
                $i=2;

                while($rowBig = $allOrders->fetch_assoc()){
                    $dateTime = $conn->query('SELECT * FROM forder WHERE id='.$rowBig["id"].';');
                    $dateTime = mysqli_fetch_assoc($dateTime);

                    echo'<div class="wrap-collabsible">
                            <input id="collapsible'.$i.'" class="toggle" type="checkbox">
                            <label for="collapsible'.$i.'" class="lbl-toggle">
                                <div class="titleDiv">
                                    <img width="40px" height="40px" src="images/icons/';if($dateTime['delivery'] == 1){
                                            echo 'delivery.svg';
                                        }
                                        else{
                                            echo 'catering.svg';
                                        }echo '" alt="pizza" style="margin-right:10px;">
                                    <h3 class="itemName">Ordine del giorno: <span style="color:#F84F31">'.htmlspecialchars($dateTime['dateAndTimePay']).'</span> </h3>
                                </div>
                            </label>
                            <div class="collapsible-content">
                                <div class="content-inner">
                                    <div class="dishDiv">';
                    echo '              <div class="itemCard orderTime">
                                            
                                            <h3 class="itemName"> Consegna:'; 
                                            if(isset($dateTime['dateAndTimeDelivered'])){
                                                echo'<span style="color:#23C552"> '.htmlspecialchars($dateTime['dateAndTimeDelivered']).'</span>';
                                            }
                                            else{
                                                echo '<span style="color:#F84F31"> In consegna</span>';
                                            } 
                    echo                    '</h3>
                                         </div>';
                        
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

                    if($dateTime["delivery"] == 0){
                        $totalPrice = $totalPrice * $dateTime["reservations"];
                        echo '      <div class="itemCard">
                                        <div class="itemRight">
                                            <h3 class="itemName"> numero prenotazioni: <span style="font-weight: bold; color: green">'.htmlspecialchars($dateTime['reservations']).'</span></h3>
                                        </div>
                                        <div class="itemLeft">
                                            <h3 class="itemName">totale: <span style="margin-right: 10px; font-weight: bold;color: red">'.$totalPrice.'€</span></h3>
                                        </div>
                                    </div>';
                    }
                    else{
                        $totalPrice += 10;
                        echo '      <div class="itemCard">
                                        <div class="itemRight">
                                            <h3 class="itemName"> indirizzo di spedizione: <span style="font-weight: bold; color: green">'.htmlspecialchars($dateTime['via']).' '.htmlspecialchars($dateTime['civ']).'</span></h3>
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
    </body>
    <?php $conn->close(); ?>
</html>