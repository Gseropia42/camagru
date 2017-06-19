<?php
/**
 * Created by PhpStorm.
 * User: gseropian
 * Date: 23/05/2017
 * Time: 14:02
 */
include_once 'config/database.php';

try {
    $dbh = new PDO($base_dsn, $base_username);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);