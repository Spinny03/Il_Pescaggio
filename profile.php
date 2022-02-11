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
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="css/cardStyles.css">
        <link rel="stylesheet" href="css/homeStyles.css">
        <link rel="stylesheet" href="css/navBarStyles.css">
        <script src="js/navbarRes.js" defer></script>
        
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>HOME</title>
    </head>
    <body>
        <div class="container">

            <nav class="navBar">
                <a href="home.php">
                    <img src="images/smallLogo.png" alt="logo" id="logo">
                </a>
                <ul class="navItems" data-visible="false">
                    <a href="home.php" class="navLink" style="color: #4e60ff">Delivery</a>
                    <a href="#" class="navLink">Catering</a>
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

        </div>  
    </body>
    <?php $conn->close(); ?>
    <script src="js/filterFood.js"></script>
    <script >filterSelection("<?php echo $_SESSION["typefood"];?>")</script>
</html>