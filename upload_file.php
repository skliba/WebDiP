<?php

include_once './baza.class.php';
include_once './virtualTime.php';
$baza = new Baza();

$allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "wma", "txt");
$extension = pathinfo($_FILES['uploadedFile']['name'], PATHINFO_EXTENSION);

if ((($_FILES["uploadedFile"]["type"] == "video/mp4") || ($_FILES["uploadedFile"]["type"] == "audio/mp3") || ($_FILES["uploadedFile"]["type"] == "audio/wma") || ($_FILES["uploadedFile"]["type"] == "image/pjpeg") || ($_FILES["uploadedFile"]["type"] == "image/gif") || ($_FILES["uploadedFile"]["type"] == "image/jpeg") || ($_FILES["uploadedFile"]["type"] == "txt/plain")) && ($_FILES["uploadedFile"]["size"] < 250000) && in_array($extension, $allowedExts)) {
    if ($_FILES["uploadedFile"]["error"] > 0) {
        echo "Return Code: " . $_FILES["uploadedFile"]["error"] . "<br />";
    } else {
        echo "Upload: " . $_FILES["uploadedFile"]["name"] . "<br />";
        echo "Type: " . $_FILES["uploadedFile"]["type"] . "<br />";
        echo "Size: " . ($_FILES["uploadedFile"]["size"] / 1024) . " Kb<br />";
        echo "Temp file: " . $_FILES["uploadedFile"]["tmp_name"] . "<br />";

        $id_emisije = $_POST['hiddenId'];
        $filename = $_FILES['uploadedFile']['name'];
        $ext = $_FILES["uploadedFile"]["type"];
        $valid = "1";
        $mod = $_SESSION['username'];


        if (file_exists("upload/" . $_FILES["uploadedFile"]["name"])) {
            echo $_FILES["uploadedFile"]["name"] . " already exists. ";
        } else {
            move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], "upload/" . $_FILES["uploadedFile"]["name"]);
            echo "Stored in: " . "upload/" . $_FILES["uploadedFile"]["name"];

            $query = "insert into materijal values(default, '$filename', 'Materijal za emisiju', '$ext', $id_emisije, '$valid', '$mod')";
            if ($baza->updateDB($query)) {
                $time = getPomak();
                $query = "insert into log values(default,'$mod','$time','Moderator $mod je dodao materijal emisije: $id_emisije materijal: $filename', 3)";
                $baza->updateDB($query);
            } 
            else {
                
            }
        }
    }
} else {
    echo "Invalid file";
}
?>