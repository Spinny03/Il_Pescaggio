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
    

    $dataID = "new";
    $dataName = "";
    $dataSurname = "";
    $photoNewEs = True;
    
    if(empty($dataPL)){
        $link = "images/icons/dish.png";
    }
    else{
        $link = "images/photoDishes/".$dataPL;
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
                <h2>Piatti nel menu</h2>
                <div class="itemCard" style="background-color: green;">
                    <form action="riders.php#pform" method="POST">
                        <button type="submit" class="itemNumber formBtn" name="edit" value="new" style="background-color: green; width:100%;">
                            Aggiungi rider
                        </button>
                    </form>
                </div>
                <?php 
                    $sql = 'SELECT * FROM rider ORDER BY id DESC;';
                    $result = $conn->query($sql); 
                    $totalPrice = 0;
                    while($row = $result->fetch_assoc()){   
                        echo '  
                            <div class="itemCard">
                                <div class="itemRight">
                                    <span class="itemNumber" style="background-color: #f3f4ff;"><img width="100%" height="100%" src="images/icons/profile.png" alt="pizza"></span>
                                    <h3 class="itemName">'.htmlspecialchars($row['riderName']).' '.htmlspecialchars($row['riderSurname']).'</h3>
                                </div>
                                <div class="itemLeft">
                                    <form action="access/ridersDB.php" method="POST">
                                        <button type="submit" class="itemNumber formBtn" name="del" value="'.$row["id"].'" style="background-color: white; margin-left:10px;">
                                            🗑️
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
                    echo "<h2>Aggiungi nuovo rider</h2>";
                }
                else{
                    echo "<h2>Conferma dati rider</h2>";
                }?>
                <div class="pSettings">
                    <form action="access/ridersDB.php" method="POST" >
                        <input type="hidden" name="idRider" value="<?php echo $dataID;?>">
                        <div class="data" id="p50">
                            <label for="name"><b>Nome</b></label>
                            <input type="text" placeholder="Mario" name="name"
                                <?php
                                    if(isset($dataName)){
                                        echo "value='".$dataName."'";
                                    }
                                ?> 
                            required>
                        </div>

                        <div class="data" id="p50">
                            <label for="surname"><b>Cognome</b></label>
                            <input type="text" placeholder="Rossi" name="surname"
                                <?php
                                    if(isset($dataSurname)){
                                        echo "value='".$dataSurname."'";
                                    }
                                ?> 
                            required>
                        </div>


                        <button type="submit" name="change" value="False" class="logbtn">Annulla modifiche</button>
                        <button style="background-color: green;" type="submit" name="change" value="add" class="logbtn">Conferma nuovo rider</button>

                    </form>
                </div>
                <form action="home.php" id="exitForm"></form>
            </div>
        </div>
    </body>
    <?php $conn->close(); ?>
</html>