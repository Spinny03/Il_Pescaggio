<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");
    $result = $conn->query('SELECT SUM(quantity) FROM cart WHERE idUser="'.$_SESSION["user"].'";');
    $result = mysqli_fetch_assoc($result); 
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="css/cardStyles.css">
        <link rel="stylesheet" href="css/homeStyles.css">
        
        
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>HOME</title>
    </head>
    <body>
        <div class="container">

            <div class="navBar">
                <img src="images/smallLogo.png" alt="logo" id="logo">
                <ul class="navItems">
                    <a href="home.php" class="navLink" style="color: #4e60ff">Delivery</a>
                    <a href="#" class="navLink">Catering</a>
                    <a href="#" class="navLink">Ordini</a>
                </ul>
                
                <a href="cart.php" class="navBtn" id="shoppingCard">
                    <?php 
                        if(!empty($result["SUM(quantity)"])){
                            echo '<span id="itemsNumber">'.$result["SUM(quantity)"].'</span>';
                        }
                    ?>
                    <img src="images/icons/blueBag.svg" alt="logo" id="shoppingSVG"> 
                </a>
                <a href="profile.php" class="navBtn" id="profileBtn">

                </a>
            </div>
            
            <div class="chooseDish">  

                <button class="choice active" onclick="filterSelection('pizza')">
                    <img width="20%" height="40%" src="images/foodType/pizza.png" alt="pizza">
                    <p>Pizza</p>
                </button>
                
                <button class="choice" onclick="filterSelection('burger')"> 
                    <img width="20%" height="40%" src="images/foodType/burger.png" alt="burger">
                    <p>Burger</p>
                </button>
                <button class="choice" onclick="filterSelection('meat')"> 
                    <img width="20%" height="40%" src="images/foodType/meat.png" alt="carne">
                    <p>Carne</p>
                </button>
                <button class="choice" onclick="filterSelection('fish')"> 
                    <img width="20%" height="40%" src="images/foodType/fish.png" alt="pesce">
                    <p>Pesce</p>
                </button>
                <button class="choice" onclick="filterSelection('vegan')"> 
                    <img width="20%" height="40%" src="images/foodType/vegan.png" alt="vegano">
                    <p>Vegano</p>
                </button>
                <button class="choice" onclick="filterSelection('desserts')"> 
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
                        $result = $conn->query("SELECT * FROM dish;");
                        while($row = $result->fetch_assoc()){
                            $cart = $conn->query('SELECT quantity FROM cart, dish WHERE idUser="'.$_SESSION["user"].'" AND  dishName="'.$row["dishName"].'" AND dish.id = cart.idDish;');
                            $cart = mysqli_fetch_assoc($cart);
                            $quantity=0;
                            if(!empty($cart["quantity"])){
                                $quantity = $cart["quantity"];
                            }
                            echo'
                                <div  class="card '.$row["dishType"].'">
                                    <img src="images/photoDishes/'.$row["photoLink"].'" class="card__image" alt="'.$row["dishName"].'" />';
                                    if(date("d") - getDate(strtotime($row["creationDate"]))["mday"] < 7 ){
                                        echo '<p class="new">&nbspNuovo&nbsp</p> ';
                                    }
                            echo'   <div class="card__overlay">
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
                                                    <button type="submit" class="smallBtn" name="less" value="'.$row["dishName"].'">
                                                        -
                                                    </button>
                                                    <input type="number" class="dishNumber" readonly value="'.$quantity.'">
                                                    <button type="submit" class="smallBtn" name="add" value="'.$row["dishName"].'">
                                                        +
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>      
                            ';
                        }
                        $conn->close();
                    ?>
                </div>
            </div>
            

        </div>  
    </body>
    <script src="js/filterFood.js"></script>
    <script >filterSelection("pizza")</script>
</html>