<?php
/*
 * Autor: Gabriel Orellana Vásquez
 * Muestra la información perteneciente al producto seleccionado en el catálogo, así como algunos 
 * datos de otros productos de categorías diferentes (si hay un pantalón como producto principal debajo
 * se mostrará una lista de 4 items que serán de cualquier categoría menos la de pantalón).
 * Al mostrar los datos conecta tanto con productos para mostrar precio, nombre y descripción como talla
 * ý color.
 * 
 * Al recibir el id de producto (catálogo.php) que se utilizará para acceder a los datos se emplearán
 * tokens para proteger la información enviada, tras ello se comparará el token creado anteriormente
 * con el token creado en esta parte validando que ambos pertenecen al mismo id que se envió.
 */
require 'config.php';
require 'database.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET["id"]) ? $_GET['id'] : ''; //En caso de que exista tomará el valor anterior y sino ''. 
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error al procesar la petición.'; //De no tener el token disponible.
    exit;
} else {
    //En caso de que todo se procese de forma correcta.
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if (trim($token) == trim($token_tmp)) {
        //Tras validar el token correctamente.
        $sql = $con->prepare("select count(id) FROM productos where id=? and activo= 1");
        $sql->bindParam(1, $id, PDO::PARAM_INT);
        $sql->execute();

        if ($sql->fetchColumn() > 0) {
            $sql = $con->prepare("select nombre, descripcion, precio, id_categoria FROM productos where"
                    . " id=? and activo= 1 limit 1");
            $sql->bindParam(1, $id, PDO::PARAM_INT);
            $sql->execute();
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            //Se recogen los valores tras realizar la consulta.
            $nombre = $row["nombre"];
            $descripcion = $row["descripcion"];
            $precio = $row["precio"];
            $id_categoria = $row["id_categoria"];

            //Se utilizará para comprobar si el directorio de img está creado.
            $dir_imagen = 'productos/' . $id . '/';
            $ruta_img = $dir_imagen . 'principal.jpg';

            if (!file_exists($dir_imagen)) {
                //En caso de que no se hayan subidos imágenes del producto se mostrará
                //una imagen de no disponible.
                $ruta_img = 'productos/Imagen_no_disponible.png';
            }

            $array_img = array(); //Para mostrar varias fotos. No se utiliza actualmente.
            if (file_exists($dir_imagen)) {

                $dir = dir($dir_imagen);

                //Solo se utilizaría si se tuviesen varias fotos de un solo producto.
                while (($archivo = $dir->read() != false)) {
                    if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
                        $imagenes[] = $dir_imagen . $archivo;
                    }
                }
                $dir->close();
            }
        }
    } else {
        echo 'Error al procesar la petición.'; //En caso de alterarse la petición.
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Detalles producto</title>
        <link rel="icon" type="image/x-icon" href="img/logotipo.png">
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/all.min.css" />
        <link rel="stylesheet" href="css/mdb.min.css" />

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

            #bttn-relacionados{
                border: none;
                background: none;
                background-color: #000;
                color: #fff;
                padding: 10px 5px;
                cursor: pointer;
                margin-left: 5px;
                border-radius: 5%;
            }

            @media (max-width: 700px){
                .card-list-products{
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            .h3-name-product{
                margin-left: 5px;
                margin-top: 5px;
            }

            .price{
                margin-right: 5px;
                margin-top: 5px;
            }


        </style>


    </head>

    <body>
        <!-- Navigation-->
        <?php include 'menu.php'; ?>

        <!-- Inicio de la página. -->
        <div class="container-body">
            <div class="container-title">
                <?php echo $nombre ?>
            </div>
            <main>
                <div class="container-img">
                    <img src="<?php echo $ruta_img ?>" alt="alt" style="width: 25em" />
                </div>

                <div class="container-info-product">
                    <div class="container-price">
                        <span><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></span>

                    </div>

                        <div class="container-details-product">
                            <div class="form-group">
                                <label for="colour" id="colour">Talla</label>
                                <select name="talla" id="talla">
                                    <option disabled selected value="">Escoge una opción
                                    </option>

                                    <?php
                                    //Despliegue de tallas.
                                    $caract = 1;
                                    $sql_options = $con->prepare("select id, valor, stock from det_prod_caracter"
                                            . " where id_producto= ? and id_caracteristica = ? order by valor asc");
                                    $sql_options->bindParam(1, $id, PDO::PARAM_INT);
                                    $sql_options->bindParam(2, $caract, PDO::PARAM_INT);
                                    $sql_options->execute();

                                    while ($row_opt = $sql_options->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row_opt['valor'] . '">' . $row_opt['valor'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="container-details-product">
                            <div class="form-group">
                                <label for="colour" id="colour">Color</label>
                                <select name="color" id="color">
                                    <option disabled selected value="">Escoge una opción
                                    </option>
                                    <?php
                                    //Despliegue de colores.
                                    $caract = 2;
                                    $sql_options2 = $con->prepare("select id, valor, stock from det_prod_caracter"
                                            . " where id_producto=? and id_caracteristica = ?");
                                    $sql_options2->bindParam(1, $id, PDO::PARAM_INT);
                                    $sql_options2->bindParam(2, $caract, PDO::PARAM_INT);
                                    $sql_options2->execute();

                                    while ($row_opt2 = $sql_options2->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row_opt2['valor'] . '">' . $row_opt2['valor'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>

                        <div class="container-details-product">
                            <div class="container-add-cart">
                                <button class="btn-add-to-cart" id="envio_inf" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">
                                    <i class="fa-solid fa-plus"></i>
                                    Añadir al carrito
                                </button>
                            </div>
                        </div>
                 <!--   </form> -->

                    <script>

                        //Función para añadir producto.
                        function addProducto(id, token) {
                            let url = 'clases/carrito.php';
                            let formData = new FormData();
                            formData.append('id', id);
                            formData.append('token', token);

                            fetch(url, {
                                method: 'POST',
                                body: formData,
                                mode: 'cors'
                            }).then(response => response.json())
                                    .then(data => {
                                        if (data.ok) { //Se accede a los elementos de carrito.php en ok = true.
                                            let elemento = document.getElementById('num_cart');
                                            elemento.innerHTML = data.numero;
                                        }

                                    })
                        }

                    </script>

                    <div class="container-details-product">
                        <div class="container-description">
                            <div class="title-description">
                                <h4>Descripción</h4>
                            </div>
                            <div class="text-description">
                                <p><?php echo $descripcion ?></p>
                            </div>
                        </div>
                    </div>
            </main>

            <!-- Sección de productos relacionados.-->
            <section class="container-related-products" style="margin-bottom: 50px;margin-top: 1em;">
                <h2>Productos relacionados</h2>
                <div class="card-list-products">
                    <?php
                    //Tras validar el token correctamente.
                    $sql = $con->prepare("select count(id) FROM productos where id!=? and activo= 1 and id_categoria!=?");
                    $sql->execute(array($id, $id_categoria));
                    if ($sql->fetchColumn() > 0) {
                        $sql = $con->prepare("select id, nombre, descripcion, precio FROM productos where id!=? "
                                . "and activo= 1 and id_categoria!=? group by id_categoria");
                        $sql->bindParam(1, $id, PDO::PARAM_INT);
                        $sql->bindParam(2, $id_categoria, PDO::PARAM_INT);
                        $sql->execute();
                        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
                    }

                    foreach ($resultado as $row) {

                        //Se recogen los valores tras realizar la consulta.
                        $nombre = $row["nombre"];
                        $descripcion = $row["descripcion"];
                        $precio = $row["precio"];
                        $id2 = $row["id"];
                        ?>

                        <div class="card">
                            <div class="card-img">
                                <img src="productos/<?php echo $id2 ?>/principal.jpg" alt="">
                                <div class="info-card">
                                    <div class="text-product">
                                        <h3 class="h3-name-product"><?php echo $nombre ?></h3>
                                        <a href="Pagina-producto.php?id=<?php echo $id2; ?>&token=
                                           <?php echo hash_hmac('sha1', $id2, KEY_TOKEN); ?>">
                                            <button id="bttn-relacionados">Más detalles</button></a>
                                    </div>
                                    <div class="price"><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></div>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                    ?>
                </div>
            </section>

        </div>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

        <!-- Contact-->
        <?php include 'footer.php'; ?>

    </body>

</html>