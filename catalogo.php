<?php
/*
 * Autor: Gabriel Orellana Vásquez.
 * Este archivo muestra una galería de productos con datos generales sobre el producto junto a un botón
 * que nos redirige a la Pagina-producto.php correspondiente a cada producto mediante el uso de su id.
 * Se utiliza hash_mac para cifrar información que se enviará al siguiente destino (en este caso se cifra el
 * id). Una vez se reciba la información del id cifrado (token) en el archivo de producto se comprobará
 * si es correcto.
 * 
 * El funcionamiento de las categorías consiste en filtrar la selección de artículos según requiera el 
 * cliente. El cifrado y la comprobación del token debe realizarse incluso aquí mismo ya que se cifra
 * la categoría en la parte de menu.php, siendo enviada de la misma manera que se envía desde catalogo.php
 * a Pagina-producto.php.
 * 
 */
require 'config.php';
require 'database.php';
$db = new Database();
$con = $db->conectar();
$sql = $con->prepare("select id, nombre, precio FROM productos where activo= 1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

/* Empiezan las categorías */
$categoria = isset($_GET["categoria"]) ? $_GET['categoria'] : ''; //En caso de que exista tomará el valor anterior y sino ''. 
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($categoria == '' && $token != '') { //En caso de no recibir una categoría concreta (todo).
    
    $sql = $con->prepare("select id, nombre, precio FROM productos where activo= 1");
    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
} else {
//En caso de que todo se procese de forma correcta.
    $token_tmp = hash_hmac('sha1', $categoria, KEY_TOKEN);

    if (trim($token) == trim($token_tmp)) {
//Tras validar el token correctamente.
        $sql = $con->prepare("select id, nombre, precio FROM productos where activo= 1 and id_categoria=?");
        $sql->bindParam(1, $categoria, PDO::PARAM_INT);
        $sql->execute();
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Catálogo</title>
        <link rel="icon" type="image/x-icon" href="img/logotipo.png">
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/all.min.css"/>
        <link rel="stylesheet" href="css/mdb.min.css"/>

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

            /*Estilos de catálogo.*/
            /* Globales */
            *::after,
            *::before,
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            h1{
                margin-top: .5em;
            }

            body{
                margin: 0 auto;
                max-width: 1200px;
                font-family: 'Lato';
                color: #333;
            }

            .icon-cart{
                width: 40px;
                height: 40px;
                stroke: #000;
            }

            .icon-cart:hover{
                cursor: pointer;
            }

            img{
                max-width: 100%;
            }

            .count-products{
                position: absolute;
                top: 55%;
                right: 0;

                background-color: #000;
                color: #fff;
                width: 25px;
                height: 25px;

                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 50%;
            }

            #contador-productos{
                font-size: 12px;
            }

            .container-cart-products{
                position: absolute;
                top: 50px;
                right: 0;

                background-color: #fff;
                width: 400px;
                z-index: 1;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.20);
                border-radius: 10px;

            }

            .cart-product{
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 30px;

                border-bottom: 1px solid rgba(0, 0, 0, 0.20);

            }

            .info-cart-product{
                display: flex;
                justify-content: space-between;
                flex: 0.8;
            }

            .titulo-producto-carrito{
                font-size: 20px;
            }

            .precio-producto-carrito{
                font-weight: 700;
                font-size: 20px;
                margin-left: 10px;
            }

            .cantidad-producto-carrito{
                font-weight: 400;
                font-size: 20px;
            }

            .icon-close{
                width: 25px;
                height: 25px;
            }

            .icon-close:hover{
                stroke: red;
                cursor: pointer;
            }

            .cart-total{
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px 0;
                gap: 20px;
            }

            .cart-total h3{
                font-size: 20px;
                font-weight: 700;
            }

            .total-pagar{
                font-size: 20px;
                font-weight: 900;
            }

            .hidden-cart{
                display: none;
            }

            /* Main */
            .container-items{
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin-bottom: .5em;
            }

            .item{
                border-radius: 10px;
            }

            .item:hover{
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.20);
            }

            .item img{
                width: 100%;
                height: 300px;
                object-fit: cover;
                border-radius: 10px 10px 0 0;
                transition: all .5s;
            }

            .item figure{
                overflow: hidden;
            }

            .item:hover img{
                transform: scale(1.2);
            }

            .info-product{
                padding: 15px 30px;
                line-height: 2;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .price{
                font-size: 18px;
                font-weight: 900;
            }

            .info-product button{
                border: none;
                background: none;
                background-color: #000;
                color: #fff;
                padding: 15px 10px;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <!-- Navigation-->
        <?php include 'menu.php'; ?>

        <!-- Inicio de la página. -->
        <h1 style="text-align: center;">Catálogo</h1>

        <div class="container-items">
            <?php
            foreach ($resultado as $row) {

                $id = $row['id'];
                $imagen = "productos/" . $id . "/principal.jpg";

                if (!file_exists($imagen)) {
                    $imagen = "productos/Imagen_no_disponible.png"; //En caso de que no se haya subido imagen asociada al producto.
                }
                ?>
                <div class="item">
                    <figure>
                        <img
                            src="<?php echo $imagen; ?>"
                            alt="producto"
                            />
                    </figure>
                    <div class="info-product">
                        <h2><?php echo $row['nombre']; ?></h2>
                        <p class="price"><?php echo $row['precio']; ?> €</p>
                        <a href="Pagina-producto.php?id=<?php echo $row['id']; ?>&token=
                           <?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>">
                            <button>Más detalles</button></a>
                    </div> <!-- hash permite tomar datos, añadir una contraseña y cifrarla.-->
                </div>
                <?php
            }
            ?>
        </div>

        <!-- Contact-->
        <?php include 'footer.php'; ?>

    </body>

</html>