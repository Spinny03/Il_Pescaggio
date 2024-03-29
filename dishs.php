<?php 
    session_start(); 
    if((empty($_SESSION["user"]) && empty($_COOKIE["user"])) || ($_SESSION["user"]!="admin@ilpescaggio.it" && $_COOKIE["user"]!="admin@ilpescaggio.it")){
        header("Location: home.php");
        exit();
    }
    if(empty($_SESSION["user"])){
        if(isset($_COOKIE["user"]) && $_COOKIE["user"]=="admin@ilpescaggio.it"){
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
    
    if(isset($_POST["edit"]) && $_POST["edit"] != "new"){
        $photoNewEs = False; 
        $data = $conn->query('SELECT * FROM dish WHERE id="'.$_POST["edit"].'";');
        $data = mysqli_fetch_assoc($data);
        $dataID = $data['id'];
        $dataName = $data["dishName"];
        $dataCost = $data["dishCost"];
        $dataDescr = $data["description"];
        $dataType = $data["dishType"];
        $dataPL = $data["photoLink"];
    }
    else{
        $dataID = "new";
        $dataName = "";
        $dataCost = "";
        $dataDescr = "";
        $dataType = "pizza";
        $dataPL = "";
        $photoNewEs = True;
        if(file_exists("images/PhotoDishes/new.jpg")){
            $dataPL = "new.jpg";
        }
        if( file_exists("images/PhotoDishes/new.png")){
            $dataPL = "new.png";
        }
        if(file_exists("images/PhotoDishes/new.jpeg")){
            $dataPL = "new.jpeg";
        }
        if(file_exists("images/PhotoDishes/new.gif")){
            $dataPL = "new.gif";
        }
    }
    
    if(empty($dataPL)){
        $link = "images/icons/dish.png";
    }
    else{
        $link = "images/photoDishes/".$dataPL;
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
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="viewport" content="width=device-width" />

        <link rel="stylesheet" href="css/navBarStyles.css">
        <link rel="stylesheet" href="css/formStyles.css">
        <link rel="stylesheet" href="css/dishsStyles.css">
        <link rel="stylesheet" href="css/scrollBarStyles.css">
        <script src="js/navbarRes.js" defer></script>
        <script src="js/footer.js" defer></script>
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>GESTIONE MENU</title>
    </head>
    <body onload="footerHeight()" onresize="footerHeight()">
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
                <a href="admin.php" class="navLink" style="color: #4e60ff">Admin</a>
                <a href="catering.php" class="navLink">Catering</a>
                <a href="orders.php" class="navLink">Ordini                     
                        <?php 
                            if(isset($_SESSION["bigNews"]) && $_SESSION["bigNews"] == "news"){
                                echo '<span id="bigNews"></span>';
                            }
                        ?>
                    </a>
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
        <div class="container">
            <div class="left">
                <h2>Piatti nel menu</h2>
                    <form action="dishs.php#pform" method="POST">
                        <button type="submit" class="itemCard addDish" name="edit" value="new">
                        <h3 class="itemName" style="color: white;">Aggiungi Piatto al menu</h3>
                            
                        </button>
                    </form>
                <?php 
                    $sql = 'SELECT * FROM dish WHERE visible=1 ORDER BY creationDate DESC;';
                    $result = $conn->query($sql); 
                    $totalPrice = 0;
                    while($row = $result->fetch_assoc()){   
                        echo '  
                                    <div class="itemCard">
                                        <div>
                                            <span class="itemNumber" style="background-color: #f3f4ff;"><img width="100%" height="100%" src="images/foodType/'.htmlspecialchars($row['dishType']).'.png" alt="pizza"></span>
                                            <h3 class="itemName">'.htmlspecialchars($row['dishName']).'</h3>
                                        </div>
                                        <div class="itemLeft">
                                            <form action="access/dishsDB.php" method="POST">
                                                <button type="submit" class="itemNumber formBtn" name="del" value="'.$row["id"].'" style="background-color: white; margin-left:10px;">
                                                    🗑️
                                                </button>
                                            </form>
                                            <form action="dishs.php#pform" method="POST">
                                                <button type="submit" class="itemNumber formBtn" name="edit" value="'.$row["id"].'" style="background-color: white; margin-left:10px;margin-right:10px;">
                                                    📝
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                ';
                    }
                ?>

            </div>

            <div class="right">
                <?php 
                if($photoNewEs){
                    echo "<h2>Crea nuovo piatto</h2>";
                }
                else{
                    echo "<h2>Conferma dati piatto</h2>";
                }?>
                <div class="pSettings">
                    <form id="pform" action="access/dishsDB.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idDishP" value="<?php echo $dataID;?>">
                        <img width="200" height="200" src="<?php echo $link; ?>" class="profilePhotoBig">
                        <label class="photoBtn" for="apply"><input class="inPhoto" type="file" name="pfile" id="apply" accept="image/*">Modifica</label>
                        <button type="submit" name="change" value="False" class="photoBtn removeBtn">Rimuovi</button>
                    </form>
                    <script>
                        document.getElementById("apply").onchange = function() {
                        document.getElementById("pform").submit();
                    }
                    </script>

                    <form action="access/dishsDB.php" method="POST" >
                        <input type="hidden" name="idDish" value="<?php echo $dataID;?>">
                        <div class="data" id="p50">
                            <label for="name"><b>Nome</b></label>
                            <input type="text" placeholder="Pizza" name="name"
                                <?php
                                    if(isset($dataName)){
                                        echo "value='".$dataName."'";
                                    }
                                ?> 
                            required>
                        </div>

                        <div class="data" id="p50">
                            <label for="price"><b>Prezzo</b></label>
                            <input type="text" placeholder="Numero senza €" name="price"
                                <?php
                                    if(isset($dataCost)){
                                        echo "value='".$dataCost."'";
                                    }
                                ?> 
                            required>
                        </div>

                        <div class="data" id="p100">
                            <label for="description"><b>Descrizione</b></label>
                            <input type="text" placeholder="Lorem ipsum dolor sit amet, consectetur adipisci elit, sed do eiusmod tempor incidunt ut labore et dolore magna aliqua." name="description" 
                                <?php 
                                    if(isset($dataDescr)){
                                        echo "value='".$dataDescr."'";
                                    }
                                ?>
                            required>
                        </div>

                        <div class="chooseDish">  
                            <input type="hidden" name="type" id="typePhp" value="">
                            <button type="button" class="choice" id="pizza" onclick="SelectType('pizza')">
                                <img width="25px" height="25px" src="images/foodType/pizza.png" alt="pizza">
                                <p>Pizza</p>
                            </button>
                            <button type="button" class="choice" id="burger" onclick="SelectType('burger')"> 
                                <img width="25px" height="25px" src="images/foodType/burger.png" alt="burger">
                                <p>Burger</p>
                            </button>
                            <button type="button" class="choice" id="meat" onclick="SelectType('meat')"> 
                                <img width="25px" height="25px" src="images/foodType/meat.png" alt="carne">
                                <p>Carne</p>
                            </button>
                            <button type="button" class="choice" id="fish" onclick="SelectType('fish')"> 
                                <img width="25px" height="25px" src="images/foodType/fish.png" alt="pesce">
                                <p>Pesce</p>
                            </button>
                            <button type="button" class="choice" id="drink"  onclick="SelectType('drink')"> 
                                <img width="25px" height="25px" src="images/foodType/drink.png" alt="Bevande">
                                <p>Bevande</p>
                            </button>
                            <button type="button" class="choice" id="desserts" onclick="SelectType('desserts')"> 
                                <img width="25px" height="25px"  src="images/foodType/desserts.png" alt="dolci">
                                <p>Dolci</p>
                            </button>
                        </div>
                        <button type="submit" name="change" value="False" class="logbtn">Annulla modifiche</button>
                        <?php 
                        if(isset($_POST["edit"]) && $_POST["edit"] != "new"){
                            echo '<button type="submit" name="change" value="True" class="logbtn">Salva le modifiche</button>';
                        }
                        else{
                            echo '<button style="background-color: green;" type="submit" name="change" value="add" class="logbtn">Aggiungi piatto</button>';
                        }
                        ?>
                    </form>
                </div>
                <form action="home.php" id="exitForm"></form>
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
    <script src="js/filterFood.js"></script>
    <script >SelectType("<?php echo $dataType;?>")</script>
    <?php $conn->close(); ?>

</html>