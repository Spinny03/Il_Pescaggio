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
                <img src="images/icons/respBtn.svg" alt="menu" id="respImg">
            </button>
        </nav>
            
        <div class="container">
            <?php 
                $allOrders = $conn->query('SELECT * FROM forder ORDER BY dateAndTimePay DESC;');
                $i=2;

                while($rowBig = $allOrders->fetch_assoc()){
                    $user = $conn->query('SELECT * FROM username WHERE email="'.$rowBig["idUser"].'";');
                    $user = mysqli_fetch_assoc($user);

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
                                    <h3 class="itemName">Ordine di: <span style="color:#F84F31">'.htmlspecialchars($user['firstName'])." ".htmlspecialchars($user['surname']).'</span> alle ore: <span style="color:#F84F31">'.htmlspecialchars($rowBig['dateAndTimePay']).'</span></h3>
                                </div>
                            </label>
                            <div class="collapsible-content">
                                <div class="content-inner">
                                    <div class="dishDiv">';
                    echo '              <div class="itemCard orderTime">
                                            
                                            '; 
                                            if($rowBig['delivery'] == 1){
                                                echo'<h3 class="itemName"> Data compimento ordine: <span style="color:#4E60FF">'.htmlspecialchars($rowBig['dateAndTimePay']).'</span> </h3>
                                                <h3 class="itemName"> <span style="color:#F84F31">delivery</span></h3>';
                                            }
                                            else{
                                                echo'<h3 class="itemName"> Data compimento ordine: <span style="color:#4E60FF">'.htmlspecialchars($rowBig['dateAndTimePay']).'</span> </h3>
                                                    <h3 class="itemName"><span style="color:#23C552">catering</span></h3>';
                                            } 
                    echo                    '
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

                    if($rowBig["delivery"] == 0){
                        $totalPrice = $totalPrice * $rowBig["reservations"];
                        echo '      <div class="itemCard">
                                        <div class="itemRight">
                                            <h3 class="itemName"> numero prenotazioni: <span style="font-weight: bold; color: green">'.htmlspecialchars($rowBig['reservations']).'</span></h3>
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
                                            <h3 class="itemName"> indirizzo di spedizione: <span style="font-weight: bold; color: green">'.htmlspecialchars($rowBig['via']).' '.htmlspecialchars($rowBig['civ']).'</span></h3>
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