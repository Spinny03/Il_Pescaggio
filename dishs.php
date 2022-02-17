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
    if(isset($_POST["edit"])){
        $data = $conn->query('SELECT * FROM dish WHERE id="'.$_POST["edit"].'";');
    }
    else{
        $data = $conn->query('SELECT * FROM dish ORDER BY creationDate ASC;');
    }
    $data = mysqli_fetch_assoc($data);
    if(empty($data["photoLink"])){
        $link = "images/icons/dish.png";
    }
    else{
        $link = "images/photoDishes/".$data["photoLink"];
    } 
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
        <link rel="stylesheet" href="css/dishsStyles.css">
        <link rel="stylesheet" href="css/scrollBarStyles.css">
        <script src="js/navbarRes.js" defer></script>
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>GESTIONE MENU</title>
    </head>
    <body>
        <?php 
            $photo = $conn->query('SELECT photoLink FROM username WHERE email="'.$_SESSION["user"].'";');
            $photo = mysqli_fetch_assoc($photo); 
            if(!empty($photo["photoLink"])){
                echo'<style>
                        a[id="profileBtn"]{
                            background: url("images/userPhoto/'.$photo["photoLink"].'");
                        }
                    </style>';
            }
            else{
                echo'<style>
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
                <a href="#" class="navLink">Catering</a>
                <a href="#" class="navLink">Ordini</a>
            </ul>
            
            <a href="cart.php" class="navBtn" id="shoppingCard" style="background-color: #4e60ff;">
                <img src="images/icons/whiteBag.svg" alt="logo" id="shoppingSVG"> 
            </a>
            <a href="profile.php" class="navBtn" id="profileBtn">

            </a>
            <button class="navBtn" id="respBtn">
                <img src="images/icons/respBtn.svg" alt="menu" id="respImg">
            </button>
        </nav>
        <div class="container">
            <div class="left">
                <h2>Piatti nel menu</h2>
                <?php 
                    $sql = 'SELECT * FROM dish;';
                    $result = $conn->query($sql); 

                    $totalPrice = 0;
                    while($row = $result->fetch_assoc()){   
                        echo '  <div class="itemCard">
                                    <div class="itemRight">
                                        <span class="itemNumber" style="background-color: #f3f4ff;"><img width="100%" height="100%" src="images/foodType/'.htmlspecialchars($row['dishType']).'.png" alt="pizza"></span>
                                        <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                                    </div>
                                    <div class="itemLeft">
                                        <form action="access/dishsDB.php" method="POST">
                                            <button type="submit" class="itemNumber formBtn" name="del" value="'.$row["dishName"].'" style="background-color: white; margin-left:10px;">
                                                🗑️
                                            </button>
                                        </form>
                                        <form action="" method="POST">
                                            <button type="submit" class="itemNumber formBtn" name="edit" value="'.$row["id"].'" style="background-color: white; margin-left:10px;margin-right:10px;">
                                                📝
                                            </button>
                                        </form>
                                    </div>
                                </div>';
                    }
                    $conn->close();
                ?>

            </div>

            <div class="right">
                <h2>Conferma dati piatto</h2>
                <div class="pSettings">
                    <form id="pform" action="access/photoDB.php" method="POST" enctype="multipart/form-data">
                        <img width="200" height="200" src="<?php echo $link; ?>" class="profilePhotoBig">
                        <label class="photoBtn" for="apply"><input class="inPhoto" type="file" name="pfile" name="pfile" id="apply" accept="image/*">Modifica</label>
                        <button type="submit" name="change" value="False" class="photoBtn removeBtn">Rimuovi</button>
                    </form>
                    <script>
                        document.getElementById("apply").onchange = function() {
                        document.getElementById("pform").submit();
                    }
                    </script>

                    <form action="access/profileDB.php" method="POST" >
                        <div class="data" id="p50">
                            <label for="name"><b>Nome</b></label>
                            <input type="text" placeholder="Pizza" name="name"
                                <?php
                                    if(isset($data["dishName"])){
                                        echo "value='".$data["dishName"]."'";
                                    }
                                ?> 
                            >
                        </div>

                        <div class="data" id="p50">
                            <label for="surname"><b>Prezzo</b></label>
                            <input type="text" placeholder="Rossi" name="surname"
                                <?php
                                    if(isset($data["dishCost"])){
                                        echo "value='".$data["dishCost"]."'";
                                    }
                                ?> 
                            >
                        </div>

                        <div class="data" id="p100">
                            <label for="description"><b>Descrizione</b></label>
                            <input type="text" placeholder="Lorem ipsum dolor sit amet, consectetur adipisci elit, sed do eiusmod tempor incidunt ut labore et dolore magna aliqua." name="email" 
                                <?php 
                                    if(isset($data["description"])){
                                        echo "value='".$data["description"]."'";
                                    }
                                ?>
                            >
                        </div>
                        <div class="chooseDish">  
                            <button class="choice" id="pizza" onclick="filterSelection('pizza')">
                                <img width="20%" height="40%" src="images/foodType/pizza.png" alt="pizza">
                                <p>Pizza</p>
                            </button>

                            <button class="choice" id="burger" onclick="filterSelection('burger')"> 
                                <img width="20%" height="40%" src="images/foodType/burger.png" alt="burger">
                                <p>Burger</p>
                            </button>
                            <button class="choice" id="meat" onclick="filterSelection('meat')"> 
                                <img width="20%" height="40%" src="images/foodType/meat.png" alt="carne">
                                <p>Carne</p>
                            </button>
                            <button class="choice" id="fish" onclick="filterSelection('fish')"> 
                                <img width="20%" height="40%" src="images/foodType/fish.png" alt="pesce">
                                <p>Pesce</p>
                            </button>
                            <button class="choice" id="vegan"  onclick="filterSelection('vegan')"> 
                                <img width="20%" height="40%" src="images/foodType/vegan.png" alt="vegano">
                                <p>Vegano</p>
                            </button>
                            <button class="choice" id="desserts" onclick="filterSelection('desserts')"> 
                                <img width="20%" height="40%"  src="images/foodType/desserts.png" alt="dolci">
                                <p>Dolci</p>
                            </button>
                        </div>
                        <button type="submit" name="change" value="False" class="logbtn">Annulla modifiche</button>
                        <button type="submit" name="change" value="True" class="logbtn">Salva le modifiche</button>
                    </form>
                </div>
                <form action="home.php" id="exitForm"></form>
            </div>
        </div>
    </body>
</html>