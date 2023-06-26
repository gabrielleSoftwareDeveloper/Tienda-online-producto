<?php
/*
 * Autor: Gabriel Orellana Vásquez
 * 
 * El acceso a esta página se ve posibilitado solo a usuarios registrados con sesión iniciada.
 * 
 * Tras aprobar la compra rellenando los datos disponibles se realiza la inserción de datos
 * del carrito de compra en la BBDD y se registra la compra (esto se realizará en informacion_capturada.php).
 * 
 * El fin de esta página es mostrar la información de los artículos a comprar, los precios finales y los botones
 * de pago junto a los cuestionarios correspondientes al sistema de payPal.
 * 
 * Paypal receptor de prueba:
 * ID: AbENXdZfJM_uaXbgApbkbZEotnDWze3m10pZ5LbuMWxR4xlTlW4SCmpbel5xdSF1p7JCeIy1liUD0tcY
 * Contraseña:EEhKsV5oxwI1Pb_VMIu9spANPrW4su9i_M_HlpbJ8VN_gi_43DDUcd94oeT2lLiv_31P6AAk--m7-Jgb
 * 
 * Sandbox o cliente de prueba:
 * Usuario: sb-pt1ux26031189@personal.example.com
 * Contraseña: 0ZEFy.5"
 */
require 'config.php';
require 'database.php';
$db = new Database();
$con = $db->conectar();

//Validando las variables de sesión (el array carrito y productos).
$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
//print_r($_SESSION);
//Inicio del array lista carrito.
$lista_carrito = array();
//Se imprime lo que existe en sesión.
if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        //Cantidad se está adicionando a la consulta pero no se añadirá realmente a la BBDD.
        $sql = $con->prepare("select id, nombre, precio,$cantidad as cantidad from productos where "
                . "id = ? and activo=1");
        $sql->bindParam(1, $clave, PDO::PARAM_INT);
        $sql->execute();
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Pasarela de pago</title>
        <link rel="icon" type="image/x-icon" href="img/logotipo.png">
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/all.min.css" />
        <link rel="stylesheet" href="css/mdb.min.css" />
        <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>"></script>
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
                text-align: center;

            }

            body {
                margin: 0 auto;
                max-width: 90%;
                font-family: 'Lato';
                color: #333;
                text-align: center;

            }

            /*Estilos de la sección carrito*/
            #cart-container {
                overflow-x: auto;
            }

            #cart-container table {
                border-collapse: collapse;
                width: 1100px;
                table-layout: fixed;
                white-space: nowrap;
            }

            #cart-container table thead {
                font-weight: 700;
            }

            #cart-container table thead td {
                background-color: #333;
                color: #fff;
                border: none;
                padding: 6px 0;
            }

            #cart-container table td {
                border: 1px solid #b6b3b3;
                text-align: center;
            }

            #cart-container table td:nth:nth-child(1),
            #cart-container table td:nth:nth-child(2) {
                width: 200px;
            }

            #cart-container table td:nth:nth-child(3),
            #cart-container table td:nth:nth-child(4),
            #cart-container table td:nth:nth-child(5) {
                width: 170px;
            }

            #cart-container table td:nth:nth-child(6) {
                width: 100px;
            }

            #cart-container table tbody img {
                width: 100px;
                height: 80px;
                object-fit: cover;
            }

            #cart-container table tbody svg {
                color: #8d8c89;
            }

            #cart-bottom .coupon>div,
            #cart-bottom .total>div {
                border: 1px solid #b6b3b3;
            }

            #cart-bottom .coupon h5,
            #cart-bottom .total h5 {
                background: #333;
                color: #fff;
                border: none;
                padding: 6px 12px;
                font-weight: 700;
            }

            #cart-bottom .coupon p,
            #cart-bottom .coupon input {
                padding: 0 12 px;
            }

            #cart-bottom .coupon input {
                height: 44px;
                margin: 0 0 20px 12px;
            }

            #cart-bottom .total div>div {
                padding: 0 12px;
            }

            #cart-bottom .coupon div>button{
                background-color: #252525;
                padding: 9px;
                color: #f7f7f7;
                gap: 5px;
                font-weight: 700;
                cursor: pointer;
                border: none;

            }
            #cart-bottom .total div>button {
                margin: 0 12px 20px 0;
                border: none;
                display: flex;
                justify-content: flex-end;
                background-color: #252525;
                padding: 9px;
                color: #f7f7f7;
                font-weight: 700;
                cursor: pointer;
                margin-left: auto;
            }

            #cart-bottom .total div{
                margin-bottom: 2em;
            }
            h5{
                margin-top: 0;
            }

            h6{
                margin:0;
                font-size: 15px;
            }
            /*Terminan los estilos del carrito.*/

        </style>
    </head>

    <body>
        <!-- Header nuevo -->
        <?php include 'menu.php'; ?>

        <!--Sección carrito-->
        <section id="cart-container" class="container my-5">
            <h4 style="margin-bottom:40px;">Detalles de pago</h4>
            <table width="100%">
                <thead>
                    <tr>
                        <td>Producto</td>
                        <td>Color</td>
                        <td>Talla</td>
                        <td>Subtotal</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($lista_carrito == null) {
                        echo '<tr><td colspan=7 class="text-center"><b>Lista vacía</b></td></tr>';
                    } else {

                        $total = 0;
                        foreach ($lista_carrito as $producto) {
                            $id = $producto['id'];
                            $nombre = $producto['nombre'];
                            $precio = $producto['precio'];
                            $cantidad = $producto['cantidad']; //Importante volver a llamarlo.
                            $subtotal = $cantidad * $precio;
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo $nombre; ?></td>
                                <td><?php echo "Color" ?></td>
                                <td><?php echo "Talla" ?></td>
                                <td>
                                    <h5 id="subtotal_<?php echo $id; ?>" name="subtotal[]">
                                        <?php echo number_format($subtotal, 2, '.', ',') . MONEDA; ?></h5>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section id="cart-bottom" class="container">
            <div class="row">

                <div class="total col-lg-6 col-md-6 col-12">
                    <div>
                        <h5>Total</h5>

                        <div class="d-flex justify-content-between" style="margin: 0;">
                            <h6>Gastos de envío</h6>
                            <p>0 <?php echo MONEDA ?> </p>

                        </div>
                        <hr class="second-hr">
                        <div class="d-flex justify-content-between" style="margin: 0;">
                            <h6>Total</h6>
                            <?php
                            if ($lista_carrito == null) {
                                echo "<p class='text-center'><b>Lista vacía</b></p>";
                            } else {
                                ?>
                                <p id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>
            
            <!-- Inicio de la zona de botones para realizar el pago. --> 

        <!-- Set up a container element for the button -->
        <div id="paypal-button-container"></div>
        </section>

        

        <script>
            // Render the PayPal button into #paypal-button-container
            paypal.Buttons({
                style: {
                    color: 'black',
                    shape: 'pill',
                    label: 'pay'
                },
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                                amount: {
                                    value: <?php echo $total; ?>
                                }
                            }]

                    });
                },
                //Si el usuario aprueba el pago se pasarán los datos a informacion_capturada.
                onApprove: function (data, actions) {
                    let URL = "clases/informacion_capturada.php";
                    actions.order.capture().then(function (detalles) {
                        alert('Pago aceptado');
                        console.log(detalles);
                        let url = "clases/informacion_capturada.php";

                        return fetch(url, {
                            method: 'post',
                            headers: {
                                'content-type': 'application/json'
                            },
                            body: JSON.stringify({
                                detalles: detalles
                            })
                        })
                    });
                },
                //Si el usuario cancela el pago.
                onCancel: function (data) {
                    alert('Pago cancelado');
                    console.log(data);
                }
            }).render('#paypal-button-container');

        </script>
        <!--Finaliza la sección del carrito.-->

        <!-- Contact-->
        <?php include 'footer.php'; ?>
        
        

    </body>

</html>

