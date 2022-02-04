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
                    <img width="20%" height="40%"  src="images/foodType/desserts.png" alt="dolci">
                    <p>Dolci</p>
                </div>
            </div>


            <div class="title">
                <h2>Piatti Disponibili:</h2>
            </div>


            <div class="dish">
                <ul class="cards">
                    <?php
                        $result = $conn->query("SELECT * FROM dish;");
                        while($row = $result->fetch_assoc()){
                            echo'
                            <li>
                                <div  class="card">
                                    <img src="images/photoDishes/'.$row["photoLink"].'" class="card__image" alt="'.$row["dishName"].'" />
                                    <div class="card__overlay">
                                        <div class="card__header">               
                                            <div class="card__header-text">
                                                <h3 class="card__title">'.$row["dishName"].'</h3>            
                                            </div>
                                        </div>
                                        <p class="card__description">'.$row["description"].'</p>
                                    </div>
                                </div>      
                            </li>';
                        }
                        $conn->close();
                    ?>
                </ul>
            </div>


        </div>  
    </body>
</html>