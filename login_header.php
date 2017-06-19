<?php
/**
 * Created by PhpStorm.
 * User: gseropian
 * Date: 22/05/2017
 * Time: 15:51
 */
session_start();

if (array_key_exists('user', $_SESSION) && $_SESSION['user'])
    header('Location: index.php');
?>
<!DOCTYPE HTML>

<HTML>
<HEAD>
    <style>
        .cross {
            background-image: url('img/icons/red_cross.png');
            background-size: contain;
            cursor: pointer;
        }
        #header_section {
            background-color: darkorange;
            width: 100%;
            padding-top:5px;
            padding-bottom: 5px;
            color: white;
            marin-bottom:5px;
        }
        .header_left {
            text-align: center;
        }

        .header_right {
            text-align: center;
        }
        @media screen and (min-width: 768px) {
            #header_section {
                display: inline-flex;
            }
            .header_right {
                text-align: right;
                padding-right:20px;
                width:50%;
            }

            .header_left {
                text-align: left;
                padding-left:20px;
                width:50%;
            }
        }
    </style>

        </HEAD>
        <BODY>
        <section id="header_section">
            <div class="header_left" >
                <p>CAMAGRU, votre meilleur ami<br><a href="galerie.php">Galerie</a></p>
            </div>
            <div class="header_right">
                <p>
                  <a href='login.php'>Connexion</a> || <a href='new_user.php'>Inscription</a>
                </p>
            </div>
        </section>