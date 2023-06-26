<?php
/*
 * Autor: Gabriel Orellana Vásquez.
 * El historial de compras mostrará las compras realizadas por el usuario registrado que haya iniciado
 * sesión y haya realizado compras por medio de PayPal (en nuestro caso). Cada compra está asociada a un id
 * de cliente por lo que a la hora de mostrar la lista de gestiones de compra la consulta utilizará la variable de sesión
 * del id del usuario que se haya conectado.
 * 
 * De no haberse registrado y conectado posteriormente esta opción no aparecerá.
 * 
 * Cada tarjeta contendrá un botón que nos redirigirá a detalles_compra.php para mostrarnos detalles
 * sobre los productos incluidos en la gestión.
 */
require 'config.php';
require 'database.php';
$db = new Database();
$con = $db->conectar();

$usuario_sesion = $_SESSION['user_id'];
$token = generarToken();
$_SESSION['token'] = $token; //Así se podrá comparar con el token enviado, evitando peticiones externas.
//Muestra los pedidos asociados al cliente.
$sql = $con->prepare("select id_transaccion, fecha, status, total from compra"
        . " where id_cliente = ? order by date(fecha) desc");
$sql->execute([$usuario_sesion]);
?>

<html>

    <head>
        <title>Historial</title>
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

        <!--Lista de pedidos. -->
        <div class="container" style=" margin-top: 2em; margin-bottom: 2em;">
            <h4 style="text-align: center;">Mis pedidos</h4>

            <hr>
            <?php while ($row = $sql->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <?php echo $row['fecha']; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Código compra: <?php echo $row['id_transaccion']; ?></h5>
                        <p class="card-text">Total: <?php echo number_format($row['total'], 2, '.', ',') . MONEDA; ?></p>
                        <a href="detalles_compra.php?orden=<?php echo $row['id_transaccion']; ?>&token=
                           <?php echo $token; ?>" class="btn btn-primary bg-dark">Detalles de compra</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Contact-->
        <?php include 'footer.php'; ?>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    </body>

</html>
