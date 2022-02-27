<?php 
    session_start(); 
    if(empty($_SESSION["rider"]) && empty($_COOKIE["rider"])){
        header("Location: ridersLogin.php");
        exit();
    }
    
    $conn = new mysqli("localhost", "root", "");  
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");
    $rider = $conn->query('SELECT * FROM rider WHERE email="'.$_SESSION["rider"].'";');
    $rider = mysqli_fetch_assoc($rider); 
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" /> 
        <link rel="stylesheet" href="css/navBarStyles.css">
        <link rel="stylesheet" href="css/formStyles.css">
        <link rel="stylesheet" href="css/scrollBarStyles.css">
        <link rel="stylesheet" href="css/cateringStyles.css">
        <link rel="stylesheet" href="css/cartStyles.css">
        <link rel="stylesheet" href="css/ordersStyles.css">

        <script src="js/navbarRes.js" defer></script>
        
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>LAVORO</title>
    </head>
    <body>
        <nav class="navBar">
            <a>
                <img src="images/smallLogo.png" alt="logo" id="logo">
            </a>
                <a class="navLink" ><?php echo $rider["riderName"]." ".$rider["riderSurname"]." &nbsp"?></a>
        </nav>
            
        <div class="container">
            <?php 
                $allOrders = $conn->query('SELECT * FROM forder WHERE idRider="'.$_SESSION["rider"].'" AND orderStatus = 3  ORDER BY dateAndTimePay DESC;');
                $i=2;

                while($rowBig = $allOrders->fetch_assoc()){
                    echo'<div class="wrap-collabsible">
                            <input id="collapsible'.$i.'" class="toggle" type="checkbox">
                            <label for="collapsible'.$i.'" class="lbl-toggle">
                                <div class="titleDiv">
                                    <img width="40px" height="40px" src="images/icons/';
                                    if($rowBig['delivery'] == 1){
                                        echo 'delivery.svg';
                                    }
                                    else{
                                        echo 'catering.svg';
                                    }
                                    echo '" alt="icon" style="margin-right:10px;">
                                    <h3 class="itemName">Ordine di: <span style="color:#F84F31">'.htmlspecialchars($rowBig['firstName'])." ".htmlspecialchars($rowBig['surname']).'</span> alle ore: <span style="color:#F84F31">'.htmlspecialchars($rowBig['dateAndTimePay']).'</span></h3>
                                </div>
                            </label>
                            <div class="collapsible-content">
                                <div class="content-inner">
                                    <div class="dishDiv">';
                    echo '              <div class="itemCard orderTime">'; 
                    if($rowBig['delivery'] == 1){
                                            echo'
                                            <h3 class="itemName"> Data compimento ordine: <span style="color:#4E60FF">'.htmlspecialchars($rowBig['dateAndTimePay']).'</span> </h3>
                                            <h3 class="itemName"> <span style="color:#F84F31">delivery</span></h3>
                                        </div>';
                    }
                    else{
                                            echo'
                                            <h3 class="itemName"> Data compimento ordine: <span style="color:#4E60FF">'.htmlspecialchars($rowBig['dateAndTimePay']).'</span> </h3>
                                            <h3 class="itemName"><span style="color:#23C552">catering</span></h3>
                                        </div>';
                    } 
                        
                    $dishs = $conn->query('SELECT * FROM orderedfood WHERE idOrder='.$rowBig["id"].';');
                    $totalPrice = 0;
                    while($row = $dishs->fetch_assoc()){

                        $cart = $conn->query('SELECT * FROM dish WHERE dish.id = '.$row["idDish"].';');
                        $cart = mysqli_fetch_assoc($cart);
                        $inCart = "";
                        if(!empty($cart["quantity"])){
                            $inCart = "cart";
                        }

                        echo '      <div class="itemCard "'.$inCart.'>
                                        <div class="itemRight">
                                            <span class="itemNumber">'.htmlspecialchars($row['quantity']).'</span>
                                            <h3 class="itemName">'.htmlspecialchars($cart['dishName']).'</h3>
                                        </div>
                                        <div class="itemLeft">
                                            <span style="margin-right: 10px; font-weight: bold;">'.htmlspecialchars($cart['dishCost']).'€</span>
                                        </div>
                                    </div>';
                                    $totalPrice += $cart['dishCost'] * $row['quantity'];
                    }
                        echo '      <div class="itemCard">
                                        <div class="itemRight">
                                        <h3 class="itemName"> Indirizzo di spedizione: <span style="font-weight: bold; color: green">'.htmlspecialchars($rowBig['via']).' '.htmlspecialchars($rowBig['civ']).', '.htmlspecialchars($rowBig['cap']).'</span></h3>
                                        </div>
                                        <div class="itemLeft">
                                            <form action="access/ridersOrdersDB.php" method="POST">
                                                <input type="hidden" name="idOrder" value="'.$rowBig["id"].'">
                                                <input type="submit" name="delivered" value="Consegnato" class="logbtn">
                                            </form>
                                        </div>
                                    </div>';

                    echo '      </div>
                            </div>
                        </div>
                    </div>';
                    $i++;
                }
            ?>    
        </div>  
    </body>
    <?php $conn->close(); ?>


</html>