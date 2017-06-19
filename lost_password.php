<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once "pdo_connect.php";
    if (isset($_POST['lost_pass']) && isset($_POST['lost_valid_pass'])) {
        $query = $dbh->prepare('SELECT * FROM user WHERE username = :valid_user OR mail = :valid_mail');
        $query->execute(array(':valid_user' => $_POST['lost_pass'], ':valid_mail' => $_POST['lost_pass']));
        $user = $query->fetch();
        if (empty($user)) {
            $error = "<p style='color:red'>Nom d'utilisateur/ Adresse mail non reconnue</p>";
        }
        else {
            $valid = $user['valid_mail'];
            $to = $user['mail'];
            $subject = "Changement de mot de passe";
            $message= "
 
                 <h2>Bonjour {$user['username']}</h2>
 
                 <p>Tu as demandé a changer de mot de passe ? <a href='localhost/camagru/pass_change.php?v=" . $valid . "'>Clique ici !</a></p>
                 ";

            $headers = 'From: webmaster@camagru.com' . "\r\n" .
                'Reply-To: webmaster@camagru.com' . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
            header('Location: reset_confirm.php');
        }
    }
    $check_header = true;
    include_once "header.php";
}?>

    <H1>Mot de passe perdu ?</H1>
    <p>Pas de souci ! Entrez votre mail ou votre pseudo ci-dessous et on vous en renvoie un nouveau</p>
    <form method="POST" action="lost_password.php">
        <input type="text" name="lost_pass" placeholder="Pseudo/E-Mail">
        <button type="submit" name="lost_valid_pass">Valider</button>
    </form>
<?php
    if (isset($error)) {
        echo($error);} ?>

<?php include_once "footer.php"; ?>