<?php

//if ($_POST && $_POST['mail'] && $_POST['password'])
$error = null;
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_submit'])) {

    include_once "pdo_connect.php";

    if (!$_POST['password'] || !$_POST['mail']) {
        $error = 'Tous les champs doivent etre remplis';
    }

    // check mail valide et existant
    if (!$error && !filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
        $error = "L'adresse mail n'est pas au bon format";

    if (!$error) {
        $test_mail = $dbh->prepare('SELECT * FROM user WHERE mail = :test_mail');
        $test_mail->execute(array(':test_mail' => $_POST['mail']));
        $user = $test_mail->fetch();
        if (empty($user))
            $error = "Adresse mail non reconnue";
    }

    //Check password -> Connect if OK
    if (!$error) {
        if (!password_verify($_POST['password'], $user['password'])) {
            $error = "Votre mot de passe est incorrect";
        }
        else if (!$user['state']) {
            $error = "Veuillez vérifier votre adresse mail";
        }
        else {
            session_start();
            $_SESSION['user'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
        }
    }
}
    include_once "login_header.php";
?>

    <section>
        <h1 style="text-align:center">CAMAGRU<br>le site qui va faire couler snapchat</h1>
        <p style="text-align:center">Identifiez-vous :</p>
        <form action="login.php" method="post">
            Votre Mail: <input type="mail" name="mail"><br>
            Votre mot de passe : <input type="text" name="password"><br>
            <input type="submit"  name="login_submit">
        </form>
        <?php if ($error) {
            echo("<p style='color:red'>" . $error ."</p>");
        } ?>
        <p>Mot de passe perdu/oublié ?<a href="lost_password.php">Cliquez ici !</a></p>
        <p>Vous n'êtes pas encore inscrit ? c'est par ici : <a href="new_user.php">Inscrivez-vous !</a></p>
    </section>
<?php include_once "footer.php"; ?>