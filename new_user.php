<?php
    $error = null;
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_submit'])) {
        include_once "pdo_connect.php";

        if (!$_POST['password'] || !$_POST['mail'] || !$_POST['username']) {
            $error = 'Tous les champs doivent etre remplis';
        }

        // Check if user deja pris
        $test_user = $dbh->prepare('SELECT username FROM user WHERE username = :test_user');
        $test_user->execute(array(':test_user' => $_POST['username']));
        $user_taken = $test_user->fetchAll();
        if (!empty($user_taken) && !$error)
            $error = "Nom d'utilisateur dèja pris";

        //Check if mail valide et disponible
        if (!$error && !filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
            $error = "L'adresse mail n'est pas au bon format";

        // Check if password et assez securisé

        if (count($_POST['password']) < 6) {
            $error = "Votre mot de passe doit contenir au moins 6 caractères";
        }
        // Check si pas d'erreur
        if (!$error) {
            $test_mail = $dbh->prepare('SELECT mail FROM user WHERE mail = :test_mail');
            $test_mail->execute(array(':test_mail' => $_POST['mail']));
            $mail_taken = $test_mail->fetchAll();
            if (!empty($mail_taken))
                $error = "Cette adresse mail est dèja utilisée";
        }

        //OK insert user
        if (!$error) {
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $valid_token = (rand(1,9999));
            $stmt = $dbh->prepare('INSERT INTO user (username, password, mail, valid_mail) VALUES (:username, :pass, :mail, :valid);');
            try {
                $stmt->execute(array(':username' => $_POST['username'], ':mail' => $_POST['mail'], ':pass' => $pass, ':valid' => $valid_token));
            } catch (Exception $e) {
                die("Requête invalide");
            }
            $to = $_POST['mail'];
            $subject = "Vos infos de connexion - Camagru";
            $message= "
            
            <h2>Bonjour {$_POST['username']}, Voila tes informations :</h2>

            <p>Nom d'utilisateur : {$_POST['username']}</p>
            <p>Mot de passe : {$_POST['password']}</p>

            <p>Active ton compte en cliquant <a href='localhost/camagru/mail_validation.php?v={$valid_token}'>ICI</a></p>
            ";

            $headers = 'From: webmaster@camagru.com' . "\r\n" .
                'Reply-To: webmaster@camagru.com' . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
            header('Location: validation.php');
        }
        include_once "login_header.php";
    }
    ?>
    <section>
        <h1 style="text-align:center">CAMAGRU<br>le site qui va faire couler snapchat</h1>
        <p style="text-align:center">Inscrivez-vous :</p>
        <form action="new_user.php" method="post">
            Votre Pseudo : <input type="text" name="username"><br>
            Votre Mail: <input type="mail" name="mail"><br>
            Votre mot de passe : <input type="password" name="password"><br>
            <input type="submit" name="new_submit">
        </form>
        <p style="color:red;"><?php echo($error) ?></p>
    </section>
<?php include_once "footer.php"; ?>
