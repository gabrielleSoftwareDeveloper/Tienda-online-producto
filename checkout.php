<?php
/*
 * Autor: Gabriel Orellana Vásquez
 * Checkout muestra la lista de productos que se comprarán, dichos artículos están conectados por su id.
 * Se ofrece la posibilidad de modificar la cantidad de los productos lo que sucede en tiempo real
 * gracias a actualizar.php que contiene una función específica para enviar datos por Json y actualizar
 * subtotales y totales o incluso eliminar el producto seleccionado con el emoticono de papelera.
 * 
 * Se puede acceder a la ventana de pago.php mediante el botón realizar pago pero, solo en caso de haber
 * iniciado sesión ($_SESSION['user_id'] , de lo contrario se redirige a la página de inicio de sesión (inicio_sesion.php).
 */

require 'config.php';
require 'database.php';
$db = new Database();
$con = $db->conectar();

//Validando las variables de sesión (el array carrito y productos).
$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

//Inicio del array lista carrito.
$lista_carrito = array();
//Se imprime lo que existe en sesión.
if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        //Cantidad se está adicionando a la consulta pero no se añadirá realmente a la BBDD.
        $sql = $con->prepare("select id, nombre, precio, $cantidad as cantidad from productos where "
                . "id = ? and activo=1");
        $sql->bindParam(1, $clave, PDO::PARAM_INT);
        $sql->execute();
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Checkout</title>
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
                text-align: center;

            }

           h5{
                margin-top: 0;
            }

            h6{
                margin:0;
                font-size: 15px;
            }
        </style>


    </head>

    <body>
        <!-- Navigation-->
        <?php include 'menu.php'; ?>

        <!--Sección carrito-->
        <section id="cart-container" class="container my-5">
            <table width="100%">
                <thead>
                    <tr>
                        <td>Producto</td>
                        <td>Color</td>
                        <td>Talla</td>
                        <td>Precio</td>
                        <td>Cantidad</td>
                        <td>Subtotal</td>
                        <td>Eliminar</td>
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
                            $cantidad = $producto['cantidad'];
                            $subtotal = $cantidad * $precio;
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo $nombre; ?></td>
                                <td><?php echo "Color" ?></td>
                                <td><?php echo "Talla" ?></td>
                                <td><?php echo number_format($precio, 2, '.', ',') . MONEDA; ?></td>
                                <td><input type="number" min="1" max="10" class="w-25 pl-1" value="<?php echo $cantidad ?>"
                                           id="cantidad_<?php echo $id; ?>" onchange="actualizarCantidad(this.value, <?php echo $id; ?>)"></td>
                                <td>
                                    <h5 id="subtotal_<?php echo $id; ?>" name="subtotal[]">
                                        <?php echo number_format($subtotal, 2, '.', ',') . MONEDA; ?></h5>
                                </td>
                                <td><a href="#" id="btn-eliminar<?php echo $id ?>" data-bs-id="<?php echo $id; ?>" data-bs-toggle="modal"
                                       data-bs-target="#eliminaModal" onclick="eliminarElemento(<?php echo $id ?>)"><i class="fas fa-trash-alt"></i></a></td>
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
                        <?php
                        if ($lista_carrito != null) {
                            if (isset($_SESSION['user_id'])) {
                                ?>
                                <button><a href="pago.php" style="color:white;">Realizar pago</a></button>
                            <?php } else { ?>
                                <button><a href="inicio_sesion.php?pago" style="color:white;">Realizar pago</a></button>
                            <?php }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>

        <!--Finaliza la sección del carrito.-->

        <!-- Contact-->
        <?php include 'footer.php'; ?>


        <script>
            function actualizarCantidad(cantidad, id) {
                let url = "clases/actualizar_carrito.php";
                let formData = new FormData();
                formData.append('action', 'agregar');
                formData.append('id', id);
                formData.append('cantidad', cantidad);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                        .then(data => {
                            if (data.ok) {
                                let subtotal = document.getElementById('subtotal_' + id);
                                subtotal.innerHTML = data.sub;

                                let total = 0
                                let lista = document.getElementsByName('subtotal[]')

                                for (let i = 0; i < lista.length; i++) { //Volver a poner la moneda.
                                    total += parseFloat(lista[i].innerHTML.replace(/[€,]/g, ''));
                                }
                                total = new Intl.NumberFormat('en-US',
                                        {
                                            minimunFractionDigits: 2
                                        }).format(total)

                                document.getElementById('total').innerHTML = total + '<?php echo MONEDA;
        ?>';
                            }
                        });
            }


// Función para eliminar el elemento con el id correspondiente
            function eliminarElemento(id) {
                // Realiza la lógica para eliminar el elemento aquí
                let btnElimina = document.getElementById("btn-eliminar" + id);
                let url = "clases/actualizar_carrito.php";
                let formData = new FormData();
                formData.append('action', 'eliminar');
                formData.append('id', id);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                        .then(data => {
                            if (data.ok) {
                                location.reload();
                            }
                        });
                console.log("Eliminando elemento con el id: " + id);
            }
        </script>

    </body>

</html>

