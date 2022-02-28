<?php
session_start();
if ((empty($_SESSION["user"]) && empty($_COOKIE["user"])) || ($_SESSION["user"] != "admin@ilpescaggio.it" && $_COOKIE["user"] != "admin@ilpescaggio.it")) {
    header("Location: home.php");
    exit();
}
if (empty($_SESSION["user"])) {
    if (isset($_COOKIE["user"]) && $_COOKIE["user"] == "admin@ilpescaggio.it") {
        $_SESSION["user"] = $_COOKIE["user"];
    }
}


    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error) {
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");
    $bag = $conn->query('SELECT SUM(quantity) FROM cart WHERE idUser = "' . $_SESSION["user"] . '" AND cart.catering = 0;');
    $bag = mysqli_fetch_assoc($bag);
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="viewport" content="width=device-width" />

    <link rel="stylesheet" href="css/navBarStyles.css">
    <link rel="stylesheet" href="css/adminStyles.css">
    <link rel="stylesheet" href="css/scrollBarStyles.css">
    <script src="js/navbarRes.js" defer></script>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <title>ADMIN</title>
</head>

<body>
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
    <nav class="navBar">
        <a href="home.php">
            <img src="images/smallLogo.png" alt="logo" id="logo">
        </a>
        <ul class="navItems" data-visible="false">
            <a href="home.php" class="navLink">Delivery</a>
            <a href="admin.php" class="navLink" style="color: #4e60ff">Admin</a>
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
        <a href="profile.php" class="navBtn" id="profileBtn"></a>
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

    <div class="containerAdmin">
        <a href="dishs.php" class="adminSetting">
            <h3>Menu</h3>
        </a>
        <a href="adminOrders.php" class="adminSetting">
            <h3>Ordini</h3>
        </a>
        <a href="riders.php" class="adminSetting">
            <h3>Riders</h3>
        </a>
    </div>
    <footer>

        <div>
            <h2>Chi siamo</h2>
            <p>Il pescaggio è un ristornate di alta cucina specializzato nella cucina di mare, ex detentore di 3 stelle michelin, in questi ultimi hanni 
                ha deciso di avventurarsi anche verso una cucina meno sofisticata ma sempre di altissimo livello.
            </p>
        </div>
        <div>
            <h2>Contatti</h2>
            <p>Telefono: +39 324 907 7196</p>
            <p>Mail: barscor75@gmail.com</p>
            <p>Partita iva: 86334519757</p>
            <h3>Sviluppato Da:</h3>
            <p>alessio.barletta.2003@calvino.edu.it</p>
            <p>filippo.spinella.2003@calvino.edu.it</p>
        </div>
        <div>
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

</html>