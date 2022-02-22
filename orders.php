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
    $bag = $conn->query('SELECT SUM(quantity) FROM cart WHERE idUser="'.$_SESSION["user"].'" AND cart.catering = 0;');
    $bag = mysqli_fetch_assoc($bag); 
    if (!isset($_SESSION["typefood"])){
        $_SESSION["typefood"] = "pizza";
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
        <title>HOME</title>
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
                <a href="home.php" class="navLink" style="color: #4e60ff">Delivery</a>
                <a href="admin.php" class="navLink">Admin</a>
                <a href="catering.php" class="navLink">Catering</a>
                <a href="#" class="navLink">Ordini</a>
            </ul>
            
            <a href="cart.php" class="navBtn" id="shoppingCard">
                <img src="images/icons/blueBag.svg" alt="logo" id="shoppingSVG"> 
            </a>
            <a href="profile.php" class="navBtn" id="profileBtn">

            </a>
            <button class="navBtn" id="respBtn">
                <img src="images/icons/respBtn.svg" alt="menu" id="respImg">
            </button>
        </nav>
            
        <div class="container">

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
                                    $dishs = $conn->query('SELECT * FROM orderedfood WHERE idOrder=1;');
                                    
                                    while($row = $dishs->fetch_assoc()){

                                        $cart = $conn->query('SELECT * FROM dish WHERE dish.id = cart.idDish AND cart.catering = 1;');
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
    </body>
</html>