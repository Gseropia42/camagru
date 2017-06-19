<?php

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['v'])) {
    include_once "pdo_connect.php";
    $query = $dbh->prepare('SELECT * FROM user WHERE valid_mail = :valid');
    try {
        $query->execute(array(':valid' => $_GET['v']));
        $result = $query->fetch();
    } catch (Exception $e) {
        die("Requête invalide");
    }
    if (empty($result))
        header('Location: login.php');
    else {
        $user_pass = $_GET['v'];
    }
}
else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_pass_submit'])) {

    include_once "pdo_connect.php";
    $query = $dbh->prepare('SELECT * FROM user WHERE valid_mail = :valid');
    try {
        $query->execute(array(':valid' => $_POST['pass_token']));
        $result = $query->fetch();
    } catch (Exception $e) {
        die("Requête invalide");
    }
    if (empty($result))
        header('Location: login.php');
    else {
        $user_pass = $result['valid_mail'];
        if ($_POST['new_pass'] == $_POST['check_new_pass']) {
            if (count($_POST['new_pass'] >= 6)) {
                $new_pass = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
                try {
                    $query = $dbh->prepare('UPDATE user SET password = :new_pass WHERE valid_mail = :token');
                    $query->execute(array(':new_pass' => $new_pass, ':token' => $_POST['pass_token']));
                } catch (Exception $e) {
                    die("Requête invalide");
                }
                header('Location: reset_validation.php');
            }
            else
                $error = "<p style='color:red'>Votre mot de passe doit contenir au moins 6 caractères</p>";
        }
        else
            $error = "<p style='color:red'>Les mots de passe ne correspondent pas</p>";
    }
}
else
    header('Location: login.php');

    $check_header = true;
    include_once "header.php";
?>

    <form method="POST">
        <input name="pass_token" value="<?php echo($user_pass) ?>" style="display: none;">
        <p>Votre nouveau mot de passe : </p><input name="new_pass" type="text" placeholder="Nouveau mot de passe">
        <p>Retapez-le : </p><input type="text" name="check_new_pass" placeholder="Nouveau mot de passe">
        <button type="submit" name="new_pass_submit">Je valide !</button>
    </form>
    <?php if (isset($error)) {echo($error);}?>

<?php include_once "footer.php"; ?>