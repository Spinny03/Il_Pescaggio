<?php 
    session_start(); 
    //echo $_SESSION["user"];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="css/homeStyles.css">
        <link rel="stylesheet" href="css/cardStyles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>HOME</title>
    </head>
    <body>
        <div class="container">

        
            <div class="navBar">
                
            </div>


            <div class="chooseDish">  
                <div class="choice"> 
                    <img width="20%" height="40%" src="images/foodType/pizza.png" alt="pizza">
                    <p>Pizza</p>
                </div>
                <div class="choice"> 
                    <img width="20%" height="40%" src="images/foodType/burger.png" alt="burger">
                    <p>Burger</p>
                </div>
                <div class="choice"> 
                    <img width="20%" height="40%" src="images/foodType/meat.png" alt="carne">
                    <p>Carne</p>
                </div>
                <div class="choice"> 
                    <img width="20%" height="40%" src="images/foodType/fish.png" alt="pesce">
                    <p>Pesce</p>
                </div>
                <div class="choice"> 
                    <img width="20%" height="40%" src="images/foodType/vegan.png" alt="vegano">
                    <p>Vegano</p>
                </div>
                <div class="choice"> 
                    <img width="20%" height="40%"  src="images/foodType/cake.png" alt="dolci">
                    <p>Dolci</p>
                </div>
            </div>


            <div class="title">
                <h2>Piatti Disponibili:</h2>
            </div>


            <div class="dish">
                <ul class="cards">
                    <?php
                        for($i=0;$i<50;$i++){
                            echo'
                            <li>
                                <div  class="card">
                                    <img src="images/carousel1.png" class="card__image" alt="nome cibo" />
                                    <div class="card__overlay">
                                        <div class="card__header">               
                                            <div class="card__header-text">
                                                <h3 class="card__title">Jessica Parker</h3>            
                                            </div>
                                        </div>
                                        <p class="card__description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores, blanditiis?</p>
                                    </div>
                                </div>      
                            </li>';
                            
                        }
                    ?>
                </ul>
            </div>


        </div>  
    </body>
</html>