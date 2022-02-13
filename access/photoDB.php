<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");  

    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    if($_POST["change"] == "False"){
        $old ="SELECT photoLink FROM username WHERE email='".$_SESSION["user"]."'";
        $oldphoto = $conn->query($old);
        $oldphoto = mysqli_fetch_assoc($oldphoto); 
        if(!empty($oldphoto["photoLink"])){
            unlink("../images/userPhoto/".$oldphoto["photoLink"]);
            $del = "UPDATE username SET photoLink='' WHERE email='".$_SESSION["user"]."'";
            $conn->query($del);
        }
        header("Location: ../profile.php");
        $conn->close();
        exit();
    }


    $target_dir = "../images/userPhoto/";
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
      $old ="SELECT photoLink FROM username WHERE email='".$_SESSION["user"]."'";
      $oldphoto = $conn->query($old);
      $oldphoto = mysqli_fetch_assoc($oldphoto); 
      if(!empty($oldphoto["photoLink"])){
          unlink("../images/userPhoto/".$oldphoto["photoLink"]);
      }
      if (move_uploaded_file($_FILES["pfile"]["tmp_name"], $target_file)) {
          $sql = "UPDATE username SET photoLink='".$_SESSION["user"] .".". $imageFileType. "' WHERE email='".$_SESSION["user"]."'";
          $conn->query($sql);
      } 
    }

    $oldname = "../images/userPhoto/".htmlspecialchars(basename( $_FILES["pfile"]["name"]));
    $newname = "../images/userPhoto/".$_SESSION["user"] .".". $imageFileType;
    rename($oldname, $newname);

    header("Location: ../profile.php");
    $conn->close();
    exit();
?>