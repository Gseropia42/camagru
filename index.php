<?php
    include_once "header.php";
    include_once "pdo_connect.php";
    $query = $dbh->prepare('SELECT url FROM images WHERE images.user_id = :user_id');
    try {
        $query->execute(array(':user_id' => $_SESSION['user_id']));
        $images = $query->fetchAll();
    } catch (Exception $e) {
        die("Requête invalide");
    }
?>
<style>

    #filter_list {
        display: inline-flex;
        margin-top: 5px;
        overflow-x: scroll;
        width: 100%;
    }
    #top_filter {
        position: absolute;
        width: 200px;
        height: 175px;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        margin-left: 200px;
        margin-top: 175px;
    }
    .apercu {
        width: 100%;
        height: 500px;
        overflow: auto;
    }
    @media screen and (min-width: 768px) {
        .apercu {
            float: right;
            width: 320px;
        }
    }
</style>

    <h2>Manuel :</h2>
    <h3>- Appuyez sur "Démarrer la webcam"</h3>
    <h3>- Choississez un filtre</h3>
    <h3>- Cliquez sur "Prendre une photo"</h3>
    <h2>- Pas de webcam ? Importez une image</h2>
    <button onclick="startWebcam();">Démarrer la webcam</button>
    <button class="pic_but" onclick="snapshot();" disabled>Prendre une photo</button>
    <p>Choisissez un filtre pour pouvoir uploader votre image</p>
    Importez un fichier :<input id="import_valid" type="file" name="myImage" accept="image/*" onchange="import_image(event)" disabled/>
<div class="apercu">
    <?php
    foreach($images as $i) {
        echo("<img src='img/pics/" . $i['url'] . "' style='width:300px;height:300px;'>");
    }
    ?>
</div>
    <div style="width:400px">
        <div id="top_filter"></div>
        <video width=400 height=400 id="video" controls autoplay></video>
        <?php include_once "get_filters_list.php"; ?>
    </div>

    <canvas  id="myCanvas" width="400" height="350" ></canvas>
</section>
<?php include_once "footer.php"; ?>
    <script>

    navigator.getUserMedia = ( navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.msGetUserMedia);

    var video;
    var webcamStream;

    function startWebcam() {
        if (navigator.getUserMedia) {
            navigator.getUserMedia (

                // constraints
                {
                    video: true,
                    audio: false
                },

                // successCallback
                function(localMediaStream) {
                    video = document.querySelector('video');
                    video.src = window.URL.createObjectURL(localMediaStream);
                    webcamStream = localMediaStream;
                },

                // errorCallback
                function(err) {
                    console.log("Erreur: " + err);
                }
            );
        } else {
            console.log("Plug-in non supporté");
        }
    }

    var canvas, ctx;
    var active_filter;
    function postAjax(url, data, success) {
        var params = typeof data == 'string' ? data : Object.keys(data).map(
            function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
        ).join('&');

        var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        xhr.open("POST", url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState>3 && xhr.status==200) { success(xhr.responseText); }
        };
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(params);
        return xhr;
    }

    function init() {

        canvas = document.getElementById("myCanvas");
        ctx = canvas.getContext('2d');
        var top = document.getElementById('top_filter');
        var wrappers = document.getElementsByClassName('filter_wrapper');
        var filters = document.getElementsByClassName('filter');
        var pic_but = document.getElementsByClassName('pic_but');
        var import_but = document.getElementById('import_valid');
        for (var i = 0; i < filters.length; i++) {
            filters[i].addEventListener('click', function() {
                pic_but[0].disabled = false;
                import_but.disabled = false;
                top.style.backgroundImage="url("+ this.src +")";
                for (var i = 0; i < wrappers.length; i++) {
                    wrappers[i].style.border = "none";
                }
                this.parentElement.style.border = "blue 1px solid";
                active_filter = this.getAttribute('data-name');
            });
        }
    }

    function snapshot(name) {
        if (!active_filter) {
            alert("Veuillez sélectionner un filtre d'abord. Si vous voulez vous prendre en photo, vous avez un téléphone.");
            return;
        }

        ctx.drawImage(video, 0,0, canvas.width, canvas.height);
        var dataURI = canvas.toDataURL('image/jpeg');
        data = {'input': dataURI, 'filter' : active_filter};
        postAjax('func/store_img.php', data, function(data){
            location.reload();
        });
    }
    function import_image(e) {
        if (!active_filter) {
            alert("Veuillez sélectionner un filtre d'abord. Si vous voulez vous prendre en photo, vous avez un téléphone.");
            return;
        }
        var file = e.target.files[0];        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(event){
            console.log("start : " + event.target.result);
            data = {'input': event.target.result, 'filter' : active_filter};
            postAjax('func/store_img.php', data, function(data){
                location.reload();
            });
        };
    }
    init();
</script>