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
    $data = $conn->query('SELECT * FROM username WHERE email ="'.$_SESSION["user"].'";');
    $data = mysqli_fetch_assoc($data); 
    if(empty($data["photoLink"])){
        $link = "images/icons/profile.png";
    }
    else{
        $link = "images/userPhoto/".$data["photoLink"];
    }
    if(isset($_SESSION["emailFail"]) && $_SESSION["emailFail"]){
        echo'<style>
                input[name="email"]{
                    background-color: rgba(255, 78, 113, 0.7);
                }
            </style>';
        $_SESSION["emailFail"] = False;
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
        <link rel="stylesheet" href="css/profileStyles.css">
        <link rel="stylesheet" href="css/navBarStyles.css">
        <link rel="stylesheet" href="css/formStyles.css">
        <link rel="stylesheet" href="css/scrollBarStyles.css">
        <script src="js/navbarRes.js" defer></script>
        <script src="js/footer.js" defer></script>
        
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>PROFILO</title>
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
        <div class="container" style="min-height: 100vh;">
            <nav class="navBar">
                <a href="home.php">
                    <img src="images/smallLogo.png" alt="logo" id="logo">
                </a>
                <ul class="navItems" data-visible="false">
                    <a href="home.php" class="navLink">Delivery</a>
                    <?php 
                        if($_SESSION["user"]=="admin@ilpescaggio.it"){
                            echo '<a href="admin.php" class="navLink">Admin</a>';
                        }
                    ?>
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
                <a href="profile.php" class="navBtn" id="profileBtn" style="border: 2.5px solid #4e60ff;">

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
            <div class="title">
                <h2>Impostazioni Account</h2>
            </div>

            <div class="pSettings">

                
                <form id="pform" action="access/photoDB.php" method="POST" enctype="multipart/form-data">
                    <img width="200" height="200" src="<?php echo $link; ?>" class="profilePhotoBig">
                    <label class="photoBtn" for="apply"><input class="inPhoto" type="file" name="pfile" id="apply" accept="image/*">Modifica</label>
                    <button type="submit" name="change" value="False" class="photoBtn removeBtn">Rimuovi</button>
                </form>
                <script>
                    document.getElementById("apply").onchange = function() {
                    document.getElementById("pform").submit();
                }
                </script>

                <form action="access/profileDB.php" method="POST" >
                    <div class="data" id="p25">
                        <label for="name"><b>Nome</b></label>
                        <input type="text" placeholder="Mario" name="name"
                            <?php
                                if(isset($data["firstName"])){
                                    echo "value='".$data["firstName"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p25">
                        <label for="surname"><b>Cognome</b></label>
                        <input type="text" placeholder="Rossi" name="surname"
                            <?php
                                if(isset($data["surname"])){
                                    echo "value='".$data["surname"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="email"><b>Email</b></label>
                        <input type="text" placeholder="nome@esempio.com" name="email" 
                            <?php 
                                if(isset($data["email"])){
                                    echo "value='".$data["email"]."'";
                                }
                            ?>  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="tel"><b>Numero di telefono</b></label>
                        <input type="text" placeholder="123-456-7890" name="tel" 
                            <?php
                                if(isset($data["tel"])){
                                    echo "value='".$data["tel"]."'";
                                }
                            ?> pattern="[0-9]{10}"
                        >
                    </div>  
                    
                    <div class="data" id="p30">
                        <label for="via"><b>Via</b></label>
                        <input type="text" placeholder="Via Sestri" name="address1" 
                            <?php
                                if(isset($data["via"])){
                                    echo "value='".$data["via"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p10">
                        <label for="civ"><b>Civ</b></label>
                        <input type="text" placeholder="17/11" name="address2" 
                            <?php
                                if(isset($data["civ"])){
                                    echo "value='".$data["civ"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p10">
                        <label for="cap"><b>Cap</b></label>
                        <input type="text" placeholder="16154" name="postcode" 
                            <?php
                                if(isset($data["cap"])){
                                    echo "value='".$data["cap"]."'";
                                }
                            ?> 
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="nCard"><b>Carta di credito</b></label>
                        <input type="text" placeholder="0123 4567 8910" name="nCard" 
                            <?php
                                if(isset($data["nCard"])){
                                    echo "value='".$data["nCard"]."'";
                                }
                            ?>
                        >
                    </div>

                    <div class="data" id="p50">
                        <label for="changPasw"><b>Cambia Password</b></label>
                        <input type="text" placeholder="Password1" name="changPasw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Deve contenere almeno un numero e una lettera maiuscola e minuscola e almeno 8 o più caratteri" minlength="8" >
                    </div>

                    <button type="submit" name="change" value="False" class="logbtn">Annulla modifiche</button>
                    <button type="submit" name="change" value="True" class="logbtn">Salva le modifiche</button>
                    <button type="submit" name="change" value="logOUT" class="removeBtn genBtn">Esci</button>
                </form>
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
</html>