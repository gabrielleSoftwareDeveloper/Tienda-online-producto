<?php
/*
 * Autor: Gabriel Orellana Vásquez
 * La página de inicio mostrará una pequeña presentación de la marca sobre la cual se ha centrado
 * el diseño junto a una galería de imágenes de los diferentes diseños que incluye la colección más actual
 * mostrando en modelos como funcionan los artículos de moda que se presentan en catálogo.
 * 
 * La galería de fotos incluye modales para cada una de las fotos lo que ayuda a ver en detalle la prenda
 * junto al modelo.
 */

require 'config.php';
require 'database.php';
$db = new Database();
$con = $db->conectar();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Inicio</title>
        <link rel="icon" type="image/x-icon" href="img/logotipo.png">
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/all.min.css" />
        <link rel="stylesheet" href="css/mdb.min.css" />

        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
        <style>

            INPUT:-webkit-autofill,
            SELECT:-webkit-autofill,
            TEXTAREA:-webkit-autofill {
                animation-name: onautofillstart
            }

            INPUT:not(:-webkit-autofill),
            SELECT:not(:-webkit-autofill),
            TEXTAREA:not(:-webkit-autofill) {
                animation-name: onautofillcancel
            }

            @keyframes onautofillstart {
            }

            @keyframes onautofillcancel {
            }

            body {
                margin: 0 auto;
                max-width: 1200px;
                font-family: 'Lato';
                color: #333;
            }

            .w3-quarter img{
                margin-bottom: -6px;
                cursor: pointer
            }
            .w3-quarter img:hover{
                opacity: 0.6;
                transition: 0.3s
            }

            .w3-opacity{
                border-top: 1px solid grey;
            }
        </style>
    </head>

    <body>
        <!-- Navigation-->
        <?php include 'menu.php'; ?>

        <!-- About section -->
        <div class="w3-container w3-light-grey w3-center w3-text-grey w3-padding-32" id="about">
            <h4><b>Nelumbo Nucífera</b></h4>
            <hr class="w3-opacity">
            <img src="Editorial/presentacion.jpg" alt="Me" class="w3-image w3-padding-32" width="600" height="650">
            <div class="w3-content w3-justify" style="max-width:600px">
                <hr class="w3-opacity">
                <h4>Nelumbo Nucífera</h4>
                <p>Nelumbo nace de la idea de mantenerse de pie aún con las adversidades que puedan presentarse, es una mezcla
                    de emociones donde tanto blanco como negro tienen lugar para lograr impartir un aprendizaje. 
                    La marca ahonda en lo sentimental, en lo justo para el medioambiente y la mejor calidad para nuestros 
                    clientes. Los diseños desenfadados y atrevidos se destinan a toda persona que quiera disfrutar del carácter
                    y la fuerza que cada prenda lleva consigo, el tallaje es amplio, los colores más reducidos pero mantienen la esencia
                    de manera exquisita.</p>

                <hr class="w3-opacity">


            </div>
        </div>


        <!-- Photo grid -->
        <div class="w3-row w3-grayscale-min">
            <div class="w3-quarter">
                <img src="Editorial/look01.jpg" style="width:100%" onclick="onClick(this)" alt="">
                <img src="Editorial/look10.jpg" style="width:100%" onclick="onClick(this)" alt="">
                <img src="Editorial/look03.jpg" style="width:100%" onclick="onClick(this)" alt="">
            </div>

            <div class="w3-quarter">
                <img src="Editorial/look04.jpg" style="width:100%" onclick="onClick(this)" alt="">
                <img src="Editorial/look05.jpg" style="width:100%" onclick="onClick(this)" alt="">
                <img src="Editorial/look06.jpg" style="width:100%" onclick="onClick(this)" alt="">
            </div>

            <div class="w3-quarter">
                <img src="Editorial/look07.jpg" style="width:100%" onclick="onClick(this)" alt="">
                <img src="Editorial/look08.jpg" style="width:100%" onclick="onClick(this)" alt="">
                <img src="Editorial/look09.jpg" style="width:100%" onclick="onClick(this)" alt="">
            </div>

            <div class="w3-quarter">
                <img src="Editorial/look02.jpg" style="width:100%" onclick="onClick(this)" alt="">
                <img src="Editorial/look11.jpg" style="width:100%" onclick="onClick(this)" alt="">
                <img src="Editorial/look12.jpg" style="width:100%" onclick="onClick(this)" alt="">
            </div>
        </div>

        <!-- Modal for full size images on click-->
        <div id="modal01" class="w3-modal w3-black" style="padding-top:0" onclick="this.style.display = 'none'">
            <span class="w3-button w3-black w3-xlarge w3-display-topright">×</span>
            <div class="w3-modal-content w3-animate-zoom w3-center w3-transparent w3-padding-64">
                <img id="img01" class="w3-image">
                <p id="caption"></p>
            </div>
        </div>



        <script>
            // Modal Image Gallery
            function onClick(element) {
                document.getElementById("img01").src = element.src;
                document.getElementById("modal01").style.display = "block";
                var captionText = document.getElementById("caption");
                captionText.innerHTML = element.alt;
            }

        </script>

        <!-- Contact-->
        <?php include 'footer.php'; ?>

    </body>

</html>
