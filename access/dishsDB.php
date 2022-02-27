<?php 
    session_start();
    $conn = new mysqli("localhost", "root", "");
    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");
    if(isset($_POST["del"])){
        $delPhoto = 'SELECT photoLink FROM dish WHERE id = "'.$_POST["del"].'";';
        $result = $conn->query($delPhoto); 
        $result = mysqli_fetch_assoc($result);
        unlink("../images/PhotoDishes/".$result["photoLink"]);
        $sql = 'UPDATE dish SET visible = 0, photoLink = "" WHERE id = "'.$_POST["del"].'";';
        $conn->query($sql); 
        $sql = 'DELETE FROM cart WHERE idDish = "'.$_POST["del"].'";';
        $conn->query($sql); 
    }
    elseif(isset($_POST["change"]) && $_POST["change"]  == "True"){
        if(!empty($_POST["name"]) || !empty($_POST["price"]) || !empty($_POST["description"] || !empty($_POST["type"]))){
            $delPhoto = 'SELECT photoLink FROM dish WHERE id = "'.$_POST["idDish"].'";';
            $result = $conn->query($delPhoto); 
            $result = mysqli_fetch_assoc($result);
            $sql = "";
            if(!empty($_POST["name"])){
                $sql .= 'dishName = "'.$_POST["name"].'",';   
            }

            if(!empty($_POST["price"])){
                $sql .= 'dishCost = "'.$_POST["price"].'",';
            }

            if(!empty($_POST["description"])){
                $sql .= 'description = "'.$_POST["description"].'",';
            }

            if(!empty($_POST["type"])){
                $sql .= 'dishType = "'.$_POST["type"].'",';
            }

            $sql = substr($sql, 0, -1);
            $conn->query('UPDATE dish SET photoLink="", visible = 0 WHERE id = "'.$_POST["idDish"].'";');
            $conn->query('INSERT INTO dish SET '.$sql.' , visible = 1, photoLink = "'.$result["photoLink"].'";');

            $id = 'SELECT id FROM dish WHERE photoLink = "'.$result["photoLink"].'";';
            $id = $conn->query($id); 
            $id = mysqli_fetch_assoc($id);

            $oldname = "../images/PhotoDishes/".$result["photoLink"];
            $imageFileType = strtolower(pathinfo($result["photoLink"], PATHINFO_EXTENSION));
            $newname = "../images/PhotoDishes/".$id["id"].".". $imageFileType;
            rename($oldname, $newname);
            $conn->query('UPDATE dish SET photoLink="'.$id["id"].".". $imageFileType.'" WHERE id="'.$id["id"].'";');
        
        }
    }
    elseif(isset($_POST["change"]) && $_POST["change"] == "add"){
        if(!empty($_POST["name"]) || !empty($_POST["price"]) || !empty($_POST["description"] || !empty($_POST["type"]))){
            $sql = "";
            if(!empty($_POST["name"])){
                $sql .= 'dishName = "'.$_POST["name"].'",';   
            }

            if(!empty($_POST["price"])){
                $sql .= 'dishCost = "'.$_POST["price"].'",';
            }

            if(!empty($_POST["description"])){
                $sql .= 'description = "'.$_POST["description"].'",';
            }

            if(!empty($_POST["type"])){
                $sql .= 'dishType = "'.$_POST["type"].'",';
            }
            $sql = substr($sql, 0, -1);
            $conn->query("INSERT INTO dish SET visible = 1, ".$sql.";");

            
            $sql = 'SELECT id FROM dish WHERE '.$sql.';';
            $sql = str_replace(",", " AND ", $sql);
            $result = $conn->query($sql);
            $result = mysqli_fetch_assoc($result);

            if(file_exists("../images/PhotoDishes/new.jpg")){
                $sql = 'UPDATE dish SET photoLink = "'.$result["id"].'.jpg" WHERE id = "'.$result["id"].'"';
                $conn->query($sql);
                rename('../images/PhotoDishes/new.jpg', '../images/PhotoDishes/'.$result["id"].'.jpg');
            }
            if( file_exists("../images/PhotoDishes/new.png")){
                $sql = 'UPDATE dish SET photoLink = "'.$result["id"].'.png" WHERE id = "'.$result["id"].'"';
                $conn->query($sql);
                rename('../images/PhotoDishes/new.png', '../images/PhotoDishes/'.$result["id"].'.png');
            }
            if(file_exists("../images/PhotoDishes/new.jpeg")){
                $sql = 'UPDATE dish SET photoLink = "'.$result["id"].'.jpeg" WHERE id = "'.$result["id"].'"';
                $conn->query($sql);
                rename('../images/PhotoDishes/new.jpeg', '../images/PhotoDishes/'.$result["id"].'.jpeg');
            }
            if(file_exists("../images/PhotoDishes/new.gif")){
                $sql = 'UPDATE dish SET photoLink = "'.$result["id"].'.gif" WHERE id = "'.$result["id"].'"';
                $conn->query($sql);
                rename('../images/PhotoDishes/new.gif', '../images/PhotoDishes/'.$result["id"].'.gif');
            }
        }
    }
    elseif(isset($_POST["idDishP"])){ 
        if($_POST["idDishP"] != "new"){ 
            if($_POST["change"] == "False" ){
                $old ="SELECT photoLink FROM dish WHERE id = '".$_POST["idDishP"]."'";
                $oldphoto = $conn->query($old);
                $oldphoto = mysqli_fetch_assoc($oldphoto); 
                if(!empty($oldphoto["photoLink"])){
                    unlink("../images/PhotoDishes/".$oldphoto["photoLink"]);
                    $del = "UPDATE dish SET photoLink = '' WHERE id = '".$_POST["idDishP"]."'";
                    $conn->query($del);
                }
                header("Location: ../dishs.php");
                $conn->close();
                exit();
            }
        
        
            $target_dir = "../images/PhotoDishes/";
            $target_file = $target_dir . basename($_FILES["pfile"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["pfile"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }
            }
            
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $uploadOk = 0;
            }
            
            if ($uploadOk != 0) {
                $old = "SELECT photoLink FROM dish WHERE id = '".$_POST["idDishP"]."'";
                $oldphoto = $conn->query($old);
                $oldphoto = mysqli_fetch_assoc($oldphoto); 
                if(!empty($oldphoto["photoLink"])){
                    unlink("../images/PhotoDishes/".$oldphoto["photoLink"]);
                }
                if (move_uploaded_file($_FILES["pfile"]["tmp_name"], $target_file)) {
                    $sql = "UPDATE dish SET photoLink = '".$_POST["idDishP"] .".". $imageFileType. "' WHERE id = '".$_POST["idDishP"]."'";
                    $conn->query($sql);
                } 
            }
        
            $oldname = "../images/PhotoDishes/".htmlspecialchars(basename( $_FILES["pfile"]["name"]));
            $newname = "../images/PhotoDishes/".$_POST["idDishP"] .".". $imageFileType;
            rename($oldname, $newname);
        
            header("Location: ../dishs.php");
            $conn->close();
            exit();
        }
        else{
            if(file_exists("../images/PhotoDishes/new.jpg")){
                unlink("../images/PhotoDishes/new.jpg");
            }
            if( file_exists("../images/PhotoDishes/new.png")){
                unlink("../images/PhotoDishes/new.png");
            }
            if(file_exists("../images/PhotoDishes/new.jpeg")){
                unlink("../images/PhotoDishes/new.jpeg");
            }
            if(file_exists("../images/PhotoDishes/new.gif")){
                unlink("../images/PhotoDishes/new.gif");
            }
            $target_dir = "../images/PhotoDishes/";
            $target_file = $target_dir . basename($_FILES["pfile"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            move_uploaded_file($_FILES["pfile"]["tmp_name"], $target_file);
            $oldname = "../images/PhotoDishes/".htmlspecialchars(basename( $_FILES["pfile"]["name"]));
            $newname = "../images/PhotoDishes/new.". $imageFileType;
            rename($oldname, $newname);
        }
    }
    header("Location: ../dishs.php");
    $conn->close();
    exit();
?>