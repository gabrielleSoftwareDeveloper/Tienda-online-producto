<?php
/*
 * Autor: Gabriel Orellana Vásquez.
 * Como ya sucedía anteriormente, gracias a la utilización de tokens para pasar información
 * de un lado a otro, se validará que todo sea correcto, junto a ello, si no se encontrase nada que mostrar
 * o sucediese un error se mostrará de nuevo el historial_compras. Lo común es que si encontramos una
 * tarjeta en el historial de compras podamos acceder a esta página con más detalles sobre los productos
 * que conformaron la compra.
 * 
 * Se mostrarán los datos de la compra general así como los datos de los productos incluidos en la
 * compra correspondiente.
 */
require 'config.php';
require 'database.php';
$db = new Database();
$con = $db->conectar();

//Recogida de datos.
$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;

if ($orden == null || $token == null || trim($token) != trim($token_session)) {
    header('historial_compras.php');
    exit;
}

//Muestra los pedidos asociados al cliente.
$sqlCompra = $con->prepare("select id, id_transaccion, fecha, total from compra"
        . " where id_transaccion = ? order by date(fecha) desc");
$sqlCompra->bindParam(1, $orden, PDO::PARAM_STR);
$sqlCompra->execute();
$rowComp = $sqlCompra->fetch(PDO::FETCH_ASSOC);
$idCompra = $rowComp['id'];

$sqlDetail = $con->prepare("select id, nombre, precio, cantidad from detalle_compra where id_compra= ?");
$sqlDetail->bindParam(1, $idCompra, PDO::PARAM_INT);
$sqlDetail->execute();
?>
<html>

    <head>
        <title>Detalles compra</title>
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
                max-width: 90%;
                font-family: 'Lato';
                color: #333;
            }


        </style>
    </head>

    <body>

        <?php include 'menu.php'; ?>

        <!--Detalles de compra-->
        <div class="container" style="margin-top: 2em; margin-bottom: 2em;">
            <div class="col-12 col-md-4 ">
                <div class="card-header">
                    <b> Detalles de la compra </b>
                </div>
                <div class="card-body">
                    <p><b>Fecha:</b> <?php echo $rowComp['fecha']; ?></p>
                    <p><b>Código de compra:</b> <?php echo $rowComp['id_transaccion']; ?></p>
                    <p><b>Total:</b> <?php echo number_format($rowComp['total'], 2, '.', ',') . MONEDA; ?></p>
                </div>
            </div>


            <div class="col-12 col-md-8">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Talla</th>
                                <th>Color</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            while ($row = $sqlDetail->fetch(PDO::FETCH_ASSOC)) {
                                $precio = $row['precio'];
                                $cantidad = $row['cantidad'];
                                $subtotal = $precio * $cantidad;
                                ?>
                                <tr>
                                    <td><?php echo $row['nombre']; ?></td>
                                    <td>Talla</td>
                                    <td>Color</td>
                                    <td><?php echo number_format($precio, 2, '.', ',') . MONEDA; ?></td>
                                    <td><?php echo $cantidad; ?></td>
                                    <td><?php echo number_format($subtotal, 2, '.', ',') . MONEDA; ?></td>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Contact-->
        <?php include 'footer.php'; ?>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    </body>

</html>