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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="css/cardStyles.css">
    <link rel="stylesheet" href="css/navBarStyles.css">
    <link rel="stylesheet" href="css/formStyles.css">
    <link rel="stylesheet" href="css/cartStyles.css">
    <script src="js/navbarRes.js" defer></script>

    <title>Document</title>
</head>
<body>

    <nav class="navBar">
        <a href="home.php">
            <img src="images/smallLogo.png" alt="logo" id="logo">
        </a>
        <ul class="navItems" data-visible="false">
            <a href="home.php" class="navLink">Delivery</a>
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
    <div class="container">
        <div class="left">
                <h2>Conferma dati</h2>
                <form action="">

                <label for="email"><b>Nome</b></label>
                <input type="text" placeholder="nome@esempio.com" name="email" required>


                </form>
        </div>
        <div class="right">
            <h2>Articoli carrello</h2>
        </div>
    </div>
</body>
</html>


<!---
<?php 
    $sql = 'SELECT dish.dishName, quantity FROM `cart`, `dish` WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish;';
    $result = $conn->query($sql); 

    echo "<table>"; // start a table tag in the HTML

    while($row = $result->fetch_assoc()){   
    echo "<tr><td>" . htmlspecialchars($row['dishName']) . "</td><td>" . htmlspecialchars($row['quantity']) . "</td></tr>"; 
    }

    echo "</table>"; 

    $conn->close();
?>
 --->