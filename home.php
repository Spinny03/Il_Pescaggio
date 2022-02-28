
<?php 
    session_start(); 
    if(empty($_SESSION["user"]) && empty($_COOKIE["user"])){
        header("Location: index.php");
        exit();
    }
    
    if(empty($_SESSION["user"])){
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
    if(!isset($_SESSION["bigNews"]) || $_SESSION["bigNews"] != "news"){
        $bigNews = $conn->query('SELECT notice FROM username WHERE email="'.$_SESSION["user"].'";');
        $bigNews = mysqli_fetch_assoc($bigNews); 
        if($bigNews["notice"] == 1){
            $_SESSION["bigNews"] = "news";
        }
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
    <script src="js/footer.js" defer></script>

    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <title>HOME</title>
</head>

<body onload="footerHeight()" onresize="footerHeight()">
    <?php
    $photo = $conn->query('SELECT photoLink FROM username WHERE email="' . $_SESSION["user"] . '";');
    $photo = mysqli_fetch_assoc($photo);
    if (!empty($photo["photoLink"])) {
        echo '<style>
                        a[id="profileBtn"]{
                            background: url("images/userPhoto/' . $photo["photoLink"] . '");
                        }
                    </style>';
    } else {
        echo '<style>
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
                <?php
                if ($_SESSION["user"] == "admin@ilpescaggio.it") {
                    echo '<a href="admin.php" class="navLink">Admin</a>';
                }
                ?>
                <a href="catering.php" class="navLink">Catering</a>
                <a href="orders.php" class="navLink">Ordini
                    <?php
                    if (isset($_SESSION["bigNews"]) && $_SESSION["bigNews"] == "news") {
                        echo '<span id="bigNews"></span>';
                    }
                    ?>
                </a>
            </ul>

            <a href="cart.php" class="navBtn" id="shoppingCard">
                <?php
                if (!empty($bag["SUM(quantity)"])) {
                    echo '<span id="itemsNumber">' . $bag["SUM(quantity)"] . '</span>';
                }
                ?>
                <img src="images/icons/blueBag.svg" alt="logo" id="shoppingSVG">
            </a>
            <a href="profile.php" class="navBtn" id="profileBtn">

            </a>

            <button class="navBtn" id="respBtn">
                <a>
                    <img src="images/icons/respBtn.svg" alt="menu" id="respImg">
                    <?php
                    if (isset($_SESSION["bigNews"]) && $_SESSION["bigNews"] == "news") {
                        echo '<span id="bigNewsSm"></span>';
                    }
                    ?>
                </a>
            </button>
        </nav>

        <div class="chooseDish">

            <button class="choice" id="pizza" onclick="filterSelection('pizza')">
                <img width="30px" height="30px" src="images/foodType/pizza.png" alt="pizza">
                <p>Pizza</p>
            </button>

            <button class="choice" id="burger" onclick="filterSelection('burger')">
                <img width="30px" height="30px" src="images/foodType/burger.png" alt="burger">
                <p>Burger</p>
            </button>
            <button class="choice" id="meat" onclick="filterSelection('meat')">
                <img width="30px" height="30px" src="images/foodType/meat.png" alt="carne">
                <p>Carne</p>
            </button>
            <button class="choice" id="fish" onclick="filterSelection('fish')">
                <img width="30px" height="30px" src="images/foodType/fish.png" alt="pesce">
                <p>Pesce</p>
            </button>
            <button class="choice" id="drink" onclick="filterSelection('drink')">
                <img width="30px" height="30px" src="images/foodType/drink.png" alt="Bevande">
                <p>Bevande</p>
            </button>
            <button class="choice" id="desserts" onclick="filterSelection('desserts')">
                <img width="30px" height="30px" src="images/foodType/desserts.png" alt="dolci">
                <p>Dolci</p>
            </button>
        </div>


        <div class="title">
            <h2>Piatti Disponibili:</h2>
        </div>


        <div class="dish">
            <div class="cards">
                <?php
                $dishs = $conn->query("SELECT * FROM dish WHERE visible=1 ORDER BY creationDate DESC;");
                while ($row = $dishs->fetch_assoc()) {
                    $cart = $conn->query('SELECT quantity FROM cart, dish WHERE idUser="' . $_SESSION["user"] . '" AND  dishName="' . $row["dishName"] . '" AND dish.id = cart.idDish AND cart.catering = 0;');
                    $cart = mysqli_fetch_assoc($cart);
                    $quantity = 0;
                    $inCart = "";
                    if (!empty($cart["quantity"])) {
                        $quantity = $cart["quantity"];
                        $inCart = "cart";
                    }
                    if (!empty($row["photoLink"]) && file_exists('images/photoDishes/' . $row["photoLink"])) {
                        $img = 'images/photoDishes/' . $row["photoLink"];
                    } else {
                        $img = "images/icons/dish.png";
                    }
                    echo '
                                <div  class="card ' . $row["dishType"] . " " . $inCart . '" id="' . $row["id"] . '">
                                    <img src="' . $img . '" class="card__image" alt="' . $row["dishName"] . '" />';
                    if (date("d") - getDate(strtotime($row["creationDate"]))["mday"] < 7 && date("m") == getDate(strtotime($row["creationDate"]))["mon"] && date("Y") == getDate(strtotime($row["creationDate"]))["year"]) {
                        echo '<p class="new">&nbspNuovo&nbsp</p> ';
                    }
                    echo '   <div class="card__overlay ' . $inCart . '">
                                        <div class="card__header">               
                                            <div class="card__header-text" style="height:scroll;">
                                                <h3 class="card__title">' . $row["dishName"] . '</h3>
                                                <p>' . $row["description"] . '</p>                  
                                            </div>
                                        </div>
                                        <div class="divPrice">
                                            <h3>' . $row["dishCost"] . '€' . '</h3>
                                            <div>
                                                <form action="access/cartDB.php" method="POST" >
                                                    <button type="submit" class="smallBtn" name="less" value="' . $row["id"] . '">
                                                        -
                                                    </button>
                                                    <input type="number" class="dishNumber" readonly value="' . $quantity . '">
                                                    <button type="submit" class="smallBtn" name="add" value="' . $row["id"] . '">
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

    <footer>

        <div class="footerDiv">
            <h2>Chi siamo</h2>
            <p>Il pescaggio è un ristornate di alta cucina specializzato nella cucina di mare, ex detentore di 3 stelle michelin, in questi ultimi hanni
                ha deciso di avventurarsi anche verso una cucina meno sofisticata ma sempre di altissimo livello.
            </p>
        </div>
        <div class="footerDiv">
            <h2>Contatti</h2>
            <p>Telefono: +39 324 907 7196</p>
            <p>Mail: barscor75@gmail.com</p>
            <p>Partita iva: 86334519757</p>
            <h3>Sviluppato Da:</h3>
            <p>alessio.barletta.2003@calvino.edu.it</p>
            <p>filippo.spinella.2003@calvino.edu.it</p>
        </div>
        <div class="footerDiv">
            <h2>Link utili</h2>
            <p><a class="footerLink" href="home.php">Home</a></p>
            <p><a class="footerLink" href="catering.php">Catering</a></p>
            <p><a class="footerLink" href="orders.php">Ordini</a></p>
            <p><a class="footerLink" href="cart.php">Carrello</a></p>
            <p><a class="footerLink" href="profile.php">Profilo</a></p>
        </div>

    </footer>

</body>
<?php $conn->close(); ?>
<script src="js/filterFood.js"></script>
<script>
    filterSelection("<?php echo $_SESSION["typefood"]; ?>")
</script>

</html>