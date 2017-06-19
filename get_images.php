<?php

include_once "pdo_connect.php";
$query = $dbh->prepare('SELECT a.url, a.id, a.user_id, b.text FROM images a LEFT JOIN comment b ON a.id = b.image_id ORDER BY a.id');
$query_2 = $dbh->prepare('SELECT user_id, image_id FROM likes');

try {
    $query->execute();
    $images = $query->fetchAll();
    $query_2->execute();
    $likes = $query_2->fetchAll();
} catch (Exception $e) {
    die("Requête invalide");
}
$pics = array();
foreach ($images as $i) {
    if (!array_key_exists($i['id'], $pics)) {
       $pics[$i['id']] = array();
       $pics[$i['id']]['url'] = $i['url'];
       $pics[$i['id']]['id'] = $i['id'];
       $pics[$i['id']]['user_id'] = $i['user_id'];
       $pics[$i['id']]['comments'] = array();
       $pics[$i['id']]['likes'] = 0;
       $pics[$i['id']]['locked'] = false;

        foreach($likes as $l) {
            if ($l['image_id'] == $pics[$i['id']]['id']) {
                $pics[$i['id']]['likes']++;
                if (!empty($_SESSION) && $_SESSION['user_id'] == $l['user_id'])
                    $pics[$i['id']]['locked'] = true;
            }
        }
    }
    if ($i['text'])
        $pics[$i['id']]['comments'][] = $i['text'];
}
$text = '';
foreach ($pics as $i) {
    $adress = $i['url'];
    $text .= '<div class="img_wrapper" style="width:320px;display: inline-block;padding:10px">';
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $i['user_id'] )
        $text .= '<form name="delete_pic" method="POST"><input style="display:none" name="pic_id" ><button type="submit" name="delete_pic" class="cross" value="' . $i['id'] . '" style="width:20px;height:20px;position:absolute;"></button></form>';
    $text .= '<img class="img" src="img/pics/' . $i['url'] . '" data-name="' .$i['url'] . '" style="height:100%;width:100%;">';
    $form = '';
    if (isset($user) && $user) {
        if ($pics[$i['id']]['locked'])
            $form = 'J\'ai liké';
        else
            $form = '<form name="add_like" method="POST"><input type="text" name="pic_id" style="display:none" value="' . $i['id'] . '"><button type="submit" name="new_like">Je like !</button></form>';
    }
    $text .= '<div style="background-color:lightskyblue;padding:5px;"><div class="like_pic" style="height:20px; float:right">' . $form . '</div>';
    if (isset($user) && $user)
        $text .= '<form style="margin-bottom:5px;" action="galerie.php" method="POST"><input name="text" type="text" placeholder="Un commentaire..."><input type="text" name="pic_id" style="display:none" value="' . $i['id'] . '"><button type="submit" name="new_comment">Publier</button></form>';
    else
        $text .= '<p>Connectez-vous pour commenter et liker les photos</p>';

        if ($i['comments']) {
        $text.= '<div style="overflow-y: scroll;max-height:60px;height:60px;background: white;">';
        foreach ($i['comments'] as $c) {
            $text.= '<div style="background:white;">'. $c .'</div>';
        }
        $text .= '</div>';
    }
    else
        $text.= '<div style="height:60px;"></div>';
    $text.= '<p>Cette photo a ' . $i['likes'] . ' like(s).</p></div></div>';
}
?>
<div id="picture_list">
    <?php echo($text) ?>
</div>