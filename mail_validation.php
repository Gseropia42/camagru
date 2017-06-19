<?php
/**
 * Created by PhpStorm.
 * User: gseropian
 * Date: 23/05/2017
 * Time: 10:33
 */


if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['v'])) {
    include_once "pdo_connect.php";
    $query = $dbh->prepare('SELECT * FROM user WHERE valid_mail = :valid');
    try {
        $results = $query->execute(array(':valid' => $_GET['v']));
        $user = $query->fetch();
    } catch (Exception $e) {
        die("Requête invalide");
    }
    if (empty($user))
        header('Location: login.php');
    else {
        $query = $dbh->prepare('UPDATE user SET state = 1 WHERE id= :id');
        try {
            $results = $query->execute(array(':id' => $user['id']));
        } catch (Exception $e) {
            die("Requête invalide");
        }
    }
}
else
    header('Location: login.php');
include_once "login_header.php";
?>
<section>
    <H1>Bravo, tu peux maintenant te connecter</H1>
    <p><a href="login.php">Retour au login</a></p>
</section>
<?php include_once "footer.php"; ?>