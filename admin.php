<?php
session_start();
if (empty($_SESSION["user"]) && empty($_COOKIE["user"])) {
    header("Location: index.php");
    exit();
}
if (empty($_SESSION["user"]) || empty($_SESSION["user"])) {
    if (isset($_COOKIE["user"])) {
        $_SESSION["user"] = $_COOKIE["user"];
    }
}

$conn = new mysqli("localhost", "root", "");
if ($conn->connect_error) {
    exit("Connessione fallita: " . $conn->connect_error);
}
$conn->query("USE Il_Pescaggio");
$bag = $conn->query('SELECT SUM(quantity) FROM cart WHERE idUser="' . $_SESSION["user"] . '" AND cart.catering = 0;');
$bag = mysqli_fetch_assoc($bag);

if (isset($_POST["edit"]) && $_POST["edit"] != "new") {
    $photoNewEs = False;
    $data = $conn->query('SELECT * FROM dish WHERE id="' . $_POST["edit"] . '";');
    $data = mysqli_fetch_assoc($data);
    $dataID = $data['id'];
    $dataName = $data["dishName"];
    $dataCost = $data["dishCost"];
    $dataDescr = $data["description"];
    $dataType = $data["dishType"];
    $dataPL = $data["photoLink"];
} else {
    $dataID = "new";
    $dataName = "";
    $dataCost = "";
    $dataDescr = "";
    $dataType = "pizza";
    $dataPL = "";
    $photoNewEs = True;
    if (file_exists("images/PhotoDishes/new.jpg")) {
        $dataPL = "new.jpg";
    }
    if (file_exists("images/PhotoDishes/new.png")) {
        $dataPL = "new.png";
    }
    if (file_exists("images/PhotoDishes/new.jpeg")) {
        $dataPL = "new.jpeg";
    }
    if (file_exists("images/PhotoDishes/new.gif")) {
        $dataPL = "new.gif";
    }
}

if (empty($dataPL)) {
    $link = "images/icons/dish.png";
} else {
    $link = "images/photoDishes/" . $dataPL;
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
    <title>GESTIONE MENU</title>
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


            <a href="riders.php">riders</a>


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
                    if(isset($_SESSION["bigNews"]) && $_SESSION["bigNews"] == "news"){
                        echo '<span id="bigNewsSm"></span>';
                    }
                ?>
            </a>
        </button>
    </nav>

    <div class="containerAdmin">
        <a href="dishs.php" class="adminSetting">
            <h3>dishes</h3>
        </a>
        <a href="adminOrders.php" class="adminSetting">
            <h3>adminOrders</h3>
        </a>
        <a href="RidersOrders.php" class="adminSetting">
            <h3>RidersOrders</h3>
        </a>
    </div>
</body>

<?php $conn->close(); ?>

</html>