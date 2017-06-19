<?php
/**
 * Created by PhpStorm.
 * User: gseropian
 * Date: 23/05/2017
 * Time: 11:53
 */
session_start();
session_destroy();
header('Location: login.php');