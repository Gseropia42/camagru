<?php
$check_header = true;
    include_once "header.php";
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once "pdo_connect.php";
        if (isset($_POST['new_comment'])) {
            $query = $dbh->prepare('INSERT INTO comment (text, user_id, image_id) VALUES (:text, :user_id, :pic_id);');
            try {
                $query->execute(array(':text' => $_POST['text'], ':user_id' => $_SESSION['user_id'], ':pic_id' => intval($_POST['pic_id'])));

            } catch (Exception $e) {
                die($e->getMessage());
            }
            try {
            $query = $dbh->prepare('SELECT a.mail, a.username FROM user a LEFT JOIN images b ON b.id = :pic_id WHERE b.user_id = a.id');
            $query->execute(array(':pic_id' => intval($_POST['pic_id'])));
            $user = $query->fetch();

                 $to = $user['mail'];
                 $subject = "Nouveau commentaire";
                 $message= "
 
                 <h2>Bonjour {$user['username']}</h2>
 
                 <p>Un nouveau commentaire a été posté sur une de tes photos</p>
                 ";

                 $headers = 'From: webmaster@camagru.com' . "\r\n" .
                     'Reply-To: webmaster@camagru.com' . "\r\n";
                 $headers .= "MIME-Version: 1.0\r\n";
                 $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                 $headers .= 'X-Mailer: PHP/' . phpversion();
                 mail($to, $subject, $message, $headers);

            } catch (Exception $e) {
                die($e->getMessage());
            }

        }
        if (isset($_POST['new_like'])) {
            $query = $dbh->prepare('SELECT * FROM likes WHERE user_id = :user_id AND image_id = :pic_id');
            try {
                $query->execute(array(':user_id' => $_SESSION['user_id'], ':pic_id' => intval($_POST['pic_id'])));
            } catch (Exception $e) {
                die($e->getMessage());
            }
            $results = $query->fetchAll();
            if (empty($results)) {
                $query = $dbh->prepare('INSERT INTO likes (user_id, image_id) VALUES (:user_id, :pic_id);');
                try {
                    $query->execute(array(':user_id' => $_SESSION['user_id'], ':pic_id' => intval($_POST['pic_id'])));
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            }
        }
        if (isset($_POST['delete_pic'])) {
            $query = $dbh->prepare('DELETE FROM images WHERE id = :pic_id');
            try {
                $query->execute(array(':pic_id' => intval($_POST['delete_pic'])));
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    };

    include_once "get_images.php";

    include_once "footer.php";
?>
<script>

</script>
