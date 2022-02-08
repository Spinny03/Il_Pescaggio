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
                    <img src="images/icons/blueBag.svg" alt="logo" id="shoppingSVG"> 
                </a>
                <a href="profile.php" class="navBtn" id="profileBtn">

                </a>
            </div>
        </div>
    </body>
    <script src="js/filterFood.js"></script>
    <script >filterSelection("pizza")</script>
</html>