<?php
/**
 * Created by PhpStorm.
 * User: gseropian
 * Date: 29/05/2017
 * Time: 14:50
 */

include_once "database.php";
    try {
        $dbh = new PDO($base_dsn, $base_username);

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DROP DATABASE IF EXISTS db_camagru";
        $dbh->exec($sql);
        $sql = "CREATE DATABASE db_camagru";

        $dbh->exec($sql);
        $sql = "USE db_camagru;
                CREATE TABLE `comment` (`id` int(11) NOT NULL,`text` text NOT NULL,`user_id` int(11) NOT NULL,`image_id` int(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                CREATE TABLE `filters` (`id` int(11) NOT NULL,`name` text NOT NULL,`adress` text NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                INSERT INTO `filters` (`id`, `name`, `adress`) VALUES(1, 'cat', 'cat.png'),(2, 'wave', 'wave.png'),(3, 'bart', 'bart.png');
                CREATE TABLE `images` (`id` int(11) NOT NULL,`url` varchar(100) NOT NULL,`user_id` int(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                CREATE TABLE `likes` (`user_id` int(11) NOT NULL,`image_id` int(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
                CREATE TABLE `user` (`id` int(11) NOT NULL,`username` varchar(15) NOT NULL,`mail` text NOT NULL,`password` text NOT NULL,`valid_mail` int(11) NOT NULL,`state` int(11) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				INSERT INTO `user` (`id`, `username`, `mail`, `password`, `valid_mail`, `state`) VALUES(29, 'jeanjacques', 'geoffreyserop@gmail.com', '$2y$10$evR1MyUZu//HqzKeO8IPjORDwOF4tvrK2g/BlfD1RG041kgYlpFbW', 8616, 1);
                ALTER TABLE `comment` ADD PRIMARY KEY (`id`);
                ALTER TABLE `images` ADD PRIMARY KEY (`id`);
                ALTER TABLE `filters` ADD PRIMARY KEY (`id`);
                ALTER TABLE `users` ADD PRIMARY KEY (`id`);";
        $dbh->exec($sql);
}
catch(PDOException $e)
	{
        echo $e->getMessage();
        die();
    }
header('Location: ../index.php');