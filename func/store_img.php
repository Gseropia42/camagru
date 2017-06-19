<?php
/**
 * Created by PhpStorm.
 * User: gseropian
 * Date: 24/05/2017
 * Time: 09:44
 */
    session_start();
    include_once "../pdo_connect.php";
    $upload_dir = "../img/pics/";
    $img = $_POST['input'];
    if(preg_match("/\.(gif|png|jpg|jpeg|bmp)$/", $img)) {
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = $upload_dir . time() . ".jpg";
        $success = file_put_contents($file, $data);

        print $success ? $file : 'Impossible de sauvegrader le fichier';
        $dest = imagecreatefromjpeg($file);
        $src = imagecreatefrompng('../img/filter/' . $_POST['filter']);

        list($width, $height) = getimagesize($file);
        list($png_width, $png_height) = getimagesize('../img/filter/' . $_POST['filter']);

        imagecopyresized($dest, $src, (0.5 * $width), (0.5 * $height), 0, 0, (0.5 * $width), (0.5 * $height), $png_width, $png_height);
        header('Content-Type: image/jpeg');
        $img_name = time() . '.jpg';
        imagejpeg($dest, '../img/pics/' . $img_name);
        imagedestroy($dest);
        imagedestroy($src);
        $query = $dbh->prepare('INSERT INTO images (url, user_id) VALUES (:url, :user_id);');
        try {
            $query->execute(array(':url' => $img_name, ':user_id' => $_SESSION['user_id']));
        } catch (Exception $e) {
            die("Requête invalide");
        }
    }
