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
    if (!isset($_SESSION["typefood"])){
        $_SESSION["typefood"] = "pizza";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="css/cardStyles.css">
        <link rel="stylesheet" href="css/homeStyles.css">
        <link rel="stylesheet" href="css/navBarStyles.css">
        <link rel="stylesheet" href="css/scrollBarStyles.css">
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
        <div class="container">

            <nav class="navBar">
                <a href="home.php">
                    <img src="images/smallLogo.png" alt="logo" id="logo">
                </a>
                <ul class="navItems" data-visible="false">
                    <a href="home.php" class="navLink" style="color: #4e60ff">Delivery</a>

                    <a href="#" class="navLink">Catering</a>
                    <a href="dishs.php" class="navLink">Piatti</a>
                    <a href="catering.php" class="navLink">Catering</a>
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
            
            <div class="chooseDish">  

                <button class="choice" id="pizza" onclick="filterSelection('pizza')">
                    <img width="20%" height="40%" src="images/foodType/pizza.png" alt="pizza">
                    <p>Pizza</p>
                </button>
                
                <button class="choice" id="burger" onclick="filterSelection('burger')"> 
                    <img width="20%" height="40%" src="images/foodType/burger.png" alt="burger">
                    <p>Burger</p>
                </button>
                <button class="choice" id="meat" onclick="filterSelection('meat')"> 
                    <img width="20%" height="40%" src="images/foodType/meat.png" alt="carne">
                    <p>Carne</p>
                </button>
                <button class="choice" id="fish" onclick="filterSelection('fish')"> 
                    <img width="20%" height="40%" src="images/foodType/fish.png" alt="pesce">
                    <p>Pesce</p>
                </button>
                <button class="choice" id="vegan"  onclick="filterSelection('vegan')"> 
                    <img width="20%" height="40%" src="images/foodType/vegan.png" alt="vegano">
                    <p>Vegano</p>
                </button>
                <button class="choice" id="desserts" onclick="filterSelection('desserts')"> 
                    <img width="20%" height="40%"  src="images/foodType/desserts.png" alt="dolci">
                    <p>Dolci</p>
                </button>
            </div>


            <div class="title">
                <h2>Piatti Disponibili:</h2>
            </div>


            <div class="dish">
                <div class="cards">
                    <?php
                        $dishs = $conn->query("SELECT * FROM dish;");
                        while($row = $dishs->fetch_assoc()){
                            $cart = $conn->query('SELECT quantity FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND  dishName="'.$row["dishName"].'" AND dish.id = cart.idDish;');
                            $cart = mysqli_fetch_assoc($cart);
                            $quantity=0;
                            $inCart = "";
                            if(!empty($cart["quantity"])){
                                $quantity = $cart["quantity"];
                                $inCart = "cart";
                            }
                            if(!empty($row["photoLink"]) && file_exists('images/photoDishes/'.$row["photoLink"])){
                                $img = 'images/photoDishes/'.$row["photoLink"];
                            }
                            else{
                                $img = "images/icons/dish.png";
                            }
                            echo'
                                <div  class="card '.$row["dishType"]." ".$inCart.'" id="'.$row["id"].'">
                                    <img src="'.$img.'" class="card__image" alt="'.$row["dishName"].'" />';
                                    if(date("d") - getDate(strtotime($row["creationDate"]))["mday"] < 7 && date("m") == getDate(strtotime($row["creationDate"]))["mon"] && date("Y") == getDate(strtotime($row["creationDate"]))["year"]){
                                        echo '<p class="new">&nbspNuovo&nbsp</p> ';
                                    }
                            echo'   <div class="card__overlay '.$inCart.'">
                                        <div class="card__header">               
                                            <div class="card__header-text">
                                                <h3 class="card__title">'.$row["dishName"].'</h3>
                                                <p>'.$row["description"].'</p>                  
                                            </div>
                                        </div>
                                        <div class="divPrice">
                                            <h3>'.$row["dishCost"].'€'.'</h3>
                                            <div>
                                                <form action="access/cartDB.php" method="POST" >
                                                    <button type="submit" class="smallBtn" name="less" value="'.$row["id"].'">
                                                        -
                                                    </button>
                                                    <input type="number" class="dishNumber" readonly value="'.$quantity.'">
                                                    <button type="submit" class="smallBtn" name="add" value="'.$row["id"].'">
                                                        +
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>      
                            ';
                        }
                    ?>
                </div>
            </div>
            

        </div>  
    </body>
    <?php $conn->close(); ?>
    <script src="js/filterFood.js"></script>
    <script >filterSelection("<?php echo $_SESSION["typefood"];?>")</script>
</html>