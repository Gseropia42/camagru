<?php
    include_once "pdo_connect.php";
    $query = $dbh->prepare('SELECT adress FROM filters');
    try {
        $results = $query->execute();
        $filters = $query->fetchAll();
    } catch (Exception $e) {
        die("Requête invalide");
    }
    $text = '';
    foreach ($filters as $f) {
        $adress = $f['adress'];
        $text .= '<div class="filter_wrapper"><img class="filter" src="img/filter/' . $f['adress'] . '" data-name="' .$f['adress'] . '" style="height:100px;width:100px;padding:0 10px"></div>';
    }
?>
<div id="filter_list" style="display:inline-flex; margin-top: 5px;">
    <p>Choisissez un filtre :</p>
    <?php echo($text) ?>
</div>
