<?php

/*
 * Autor: Gabriel Orellana Vásquez.
 * 
 * Tras la realización de la compra (se ha pulsado uno de los botones de pago en pago.php y se ha 
 * aceptado la compra)se recogen todos los datos en un array multidimensional derivado de PayPal del 
 * cual se obtienen datos específicos necesarios para registrar la información en nuestrsa BBDD donde
 * se insertarán id de la transacción, el total de la compra, email, etc en la tabla compra, así como,
 * la información respectiva a la tabla detalle_compra.
 * 
 */

require '../config.php';
require '../database.php';
$db = new Database();
$con = $db->conectar();

$json = file_get_contents('php://input'); //Toma la información desde la función de AJAX.
$datos = json_decode($json, true);

if (is_array($datos)) {
    
    $id_usuario = $_SESSION['user_id'];

    //Se toman como referencia los índices del array resultante de $datos tras realizar la compra.
    $id_transaccion = $datos['detalles']['id'];
    $total = $datos['detalles']['purchase_units'][0]['amount']['value'];
    $status = $datos['detalles']['status'];
    $fecha = $datos['detalles']['update_time'];
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha)); //Conversión.
    $email = $datos['detalles']['payer']['email_address'];
    $id_cliente = $datos['detalles']['payer']['payer_id'];

    //Adición a la BBDD.
    $sql = $con->prepare("insert into compra (id_transaccion, fecha, status, email, id_cliente, total)"
            . "values (?,?,?,?,?,?)");
    $sql->bindParam(1,$id_transaccion, PDO::PARAM_STR);
    $sql->bindParam(2,$fecha_nueva, PDO::PARAM_STR);
    $sql->bindParam(3,$status, PDO::PARAM_STR);
    $sql->bindParam(4,$email, PDO::PARAM_STR);
    $sql->bindParam(5,$id_usuario, PDO::PARAM_INT);
    $sql->bindParam(6,$total, PDO::PARAM_INT);
   //$sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_usuario, $total]);
    $sql->execute();
    $id = $con->lastInsertId();

    if ($id > 0) {

        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

        if ($productos != null) {

            foreach ($productos as $clave => $cantidad) {
                //Cantidad se está adicionando a la consulta pero no se añadirá realmente a la BBDD.
                $sql = $con->prepare("select id, nombre, precio from productos where "
                        . "id = ? and activo=1");
                $sql->execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                $precio = $row_prod['precio'];

                $sql_insert = $con->prepare("insert into detalle_compra (id_compra, id_producto, nombre, precio, cantidad)"
                        . "values(?,?,?,?,?)");
                $sql_insert->execute([$id, $clave, $row_prod['nombre'], $precio, $cantidad]);
            }
            
            //Se elimina el contenido del carrito tras finalizar la compra.
        unset($_SESSION['carrito']);
        }
        
    }
}
