<?php
    //if (session_status() === PHP_SESSION_NONE)
    session_start();

    $user = null;
    if (!isset($check_header) || isset($_SESSION['user_id'])) {
        if (array_key_exists('user', $_SESSION) && $_SESSION['user']) ;
        $user = $_SESSION['user'];
        if (!$user) {
            header('Location: login.php');
        }
    }
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
                margin-bottom: 5px;
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
                <p>CAMAGRU, votre meilleur ami<br><a href="galerie.php">Galerie</a><?php if (isset($user) && $user) {echo(' | <a href="index.php">Prendre une photo</a>');}?></p>
            </div>
            <div class="header_right">
                <p>
                    <?php
                        if (isset($user) && $user) {
                            echo("Bonjour " . $user . " | ");
                            echo("<a href='logout.php'>Déconnexion</a>");
                        }
                        else {
                            echo("<a href='login.php'>Connexion</a> || <a href='new_user.php'>Inscription</a>");
                        }
                    ?>
                </p>
            </div>
        </section>