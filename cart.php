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
    $data = $conn->query('SELECT * FROM username WHERE email ="'.$_SESSION["user"].'";');
    $data = mysqli_fetch_assoc($data); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="viewport" content="width=device-width" />

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
            <h2>Articoli carrello</h2>
            <?php 
                $sql = 'SELECT dish.dishName, quantity, dishCost FROM `cart`, `dish` WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish;';
                $result = $conn->query($sql); 

                //echo "<table>"; // start a table tag in the HTML
                $totalPrice = 0;
                while($row = $result->fetch_assoc()){   
                //echo "<tr><td>" . htmlspecialchars($row['dishName']) . "</td><td>" . htmlspecialchars($row['quantity']) . "</td></tr>"; 
                    echo '  <div class="itemCard">
                                <div class="itemRight">
                                    <span class="itemNumber">'.htmlspecialchars($row['quantity']).'</span>
                                    <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                                </div>
                                <div class="itemLeft">
                                '.htmlspecialchars($row['dishCost']).'€
                                </div>
                            </div>';
                    $totalPrice =  $totalPrice + intval(htmlspecialchars($row['dishCost'])*intval(htmlspecialchars($row['quantity'])));
                }
                

                //echo "</table>"; 

                $conn->close();
            ?>
            

        </div>

        <div class="right">
                <h2>Conferma dati</h2>
                <form action="access/profileDB.php" method="POST">
                    <div class="data" id="p50">
                        <label for="name"><b>Nome</b></label>
                        <input type="text" placeholder="Mario" name="name"
                            <?php
                                if(isset($data["firstName"])){
                                    echo "value='".$data["firstName"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="surname"><b>Cognome</b></label>
                        <input type="text" placeholder="Rossi" name="surname"
                            <?php
                                if(isset($data["surname"])){
                                    echo "value='".$data["surname"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p100">
                        <label for="via"><b>Via</b></label>
                        <input type="text" placeholder="Via Sestri" name="via" 
                            <?php
                                if(isset($data["via"])){
                                    echo "value='".$data["via"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="civ"><b>Civ</b></label>
                        <input type="text" placeholder="17/11" name="civ" 
                            <?php
                                if(isset($data["civ"])){
                                    echo "value='".$data["civ"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="cap"><b>Cap</b></label>
                        <input type="text" placeholder="16153" name="cap" 
                            <?php
                                if(isset($data["cap"])){
                                    echo "value='".$data["cap"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p100">
                        <label for="nCard"><b>Carta di credito</b></label>
                        <input type="text" placeholder="0123 4567 7890" name="nCard" 
                            <?php
                                if(isset($data["nCard"])){
                                    echo "value='".$data["nCard"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p70">
                        <label for="nCard"><b>Data di scadenza</b></label>
                        <input type="text" placeholder="12/26" name="expireCard">
                    </div>

                    <div class="data" id="p30">
                        <label for="nCard"><b>CVV</b></label>
                        <input type="text" placeholder="123" name="expireCard">
                    </div>  

                    <div class="priceRecap data" id="p100">
                        <div class="innerPrice foodPrice">
                            <h3>Cibo:</h3>
                            <h4><?php echo $totalPrice; ?>€</h4>
                        </div>
                            
                        <div class="innerPrice deliveryPrice">
                            <h3>Consegna:</h3>
                            <h4>10€</h4>
                        </div>

                        <div class="innerPrice totalPrice">
                            <h3>Totale:</h3>
                            <h4><?php echo $totalPrice+10; ?>€</h4>
                        </div>
                    </div>

                    <button type="submit" name="login" class="delBtn">Annulla</button> 
                    <button type="submit" name="login" class="confBtn">Conferma oridne</button>          

                </form>



        </div>
    </div>
</body>
</html>


<!---
<?php 
    $sql = 'SELECT dish.dishName, quantity FROM `cart`, `dish` WHERE idUser="'.$_SESSION["user"].'" AND dish.id = cart.idDish;';
    $result = $conn->query($sql); 

    //echo "<table>"; // start a table tag in the HTML

    while($row = $result->fetch_assoc()){   
    //echo "<tr><td>" . htmlspecialchars($row['dishName']) . "</td><td>" . htmlspecialchars($row['quantity']) . "</td></tr>"; 
    echo '  <div class="itemCard">
                <div class="itemRight">
                    <span class="itemNumber">'.htmlspecialchars($row['quantity']).'</span>
                    <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                </div>
                <div class="itemLeft">
                    54€
                </div>
            </div>';
    }

    //echo "</table>"; 

    $conn->close();
?>
 --->