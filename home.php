<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");
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
                    <a href="#" class="navLink" style="color: #4e60ff">Delivery</a>
                    <a href="#" class="navLink">Catering</a>
                    <a href="#" class="navLink">Ordini</a>
                </ul>
                
                <a href="#" class="navBtn" id="shoppingCard">
                    <span id="itemsNumber">0</span>
                    <img src="images/navbar/shopping.svg" alt="logo" id="shoppingSVG"> 
                </a>
                <a href="#" class="navBtn" id="profileBtn">

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
                                                <button class="smallBtn" min="0">
                                                    -
                                                </button>
                                                <input type="number" class="dishNumber" value="0">
                                                <button class="smallBtn" min="0">
                                                    +
                                                </button>
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