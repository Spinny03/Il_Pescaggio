<?php 
    session_start(); 
    $conn = new mysqli("localhost", "root", "");  

    if ($conn->connect_error){
        exit("Connessione fallita: " . $conn->connect_error);
    }
    $conn->query("USE Il_Pescaggio");

    $target_dir = "../images/userPhoto/";
    $target_file = $target_dir . basename($_FILES["pfile"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    if(isset($_POST["submit"])) {
      $check = getimagesize($_FILES["pfile"]["tmp_name"]);
      if($check !== false) {
        $uploadOk = 1;
      } else {
        $uploadOk = 0;
      }
    }
    //gestire omonìmia file rinominare file con email
    if (file_exists($target_file)) {
      $uploadOk = 0;
    }
    
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      $uploadOk = 0;
    }
    

    if ($uploadOk != 0) {
        if (move_uploaded_file($_FILES["pfile"]["tmp_name"], $target_file)) {
            $old ="SELECT photoLink FROM username WHERE email='".$_SESSION["user"]."'";
            $oldphoto = $conn->query($old);
            $oldphoto = mysqli_fetch_assoc($oldphoto); 
            if(!empty($oldphoto["photoLink"])){
                unlink("../images/userPhoto/".$oldphoto["photoLink"]);
            }
            $sql ="UPDATE username SET photoLink='". htmlspecialchars( basename( $_FILES["pfile"]["name"])). "' WHERE email='".$_SESSION["user"]."'";
            $conn->query($sql);
        } 
    }
    header("Location: ../profile.php");
    $conn->close();
    exit();
?>