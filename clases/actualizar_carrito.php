<?php

/*
 * Autor: Gabriel Orellana Vásquez.
 * Los datos que se validarán como true o false a través de $datos['ok'] y se enviarán al script de
 * checkout para ser recibidos (ajax).
 * 
 * Gracias a la función agregar() se podrá mpdificar la cantidad de productos que se requieran,
 * mostrando en tiempo real el precio del subtotal y el total.
 */

require '../config.php';
require '../database.php';


if (isset($_POST['action'])) {

    $action = $_POST['action'];
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    //$talla = isset($_POST['talla']) ? $_POST['talla'] : 0;
    //$color = isset($_POST['color']) ? $_POST['color'] : 0;
    

    if ($action == 'agregar') {

        $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 0;
        $respuesta = agregar($id, $cantidad);
        
        if ($respuesta > 0){
            $datos['ok'] = true;
        } else{
            $datos['ok'] = false;
        }
        
        $datos['sub'] = MONEDA . number_format($respuesta, 2, '.', ',');
    } else if($action == 'eliminar'){
        $datos['ok'] = eliminar($id);
    }
        else{
       $datos['ok'] = false; 
    }
} else{
    //Si no llega action.
    $datos['ok'] = false;
}

//Función para enviar datos de vuelta tras la petición.
echo json_encode($datos);

function agregar($id, $cantidad) {

    $res = 0;

    //Validación del id y la cantidad. 
    if ($id > 0 && $cantidad > 0 && is_numeric($cantidad)) {
        if (isset($_SESSION['carrito']['productos'][$id])) {
            $_SESSION['carrito']['productos'][$id] = $cantidad;

            $db = new Database();
            $con = $db->conectar();
            
            $sql = $con->prepare("select precio FROM productos where id=? and activo= 1 limit 1");
            $sql->bindParam(1,$id, PDO::PARAM_INT);
            $sql->execute(); 
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $precio = $row["precio"];
            $res = $cantidad * $precio;
            
            return $res;
        } else{
            return $res;
        }
    }
}


function eliminar($id){
    if(isset($_SESSION['carrito']['productos'][$id])){
        unset($_SESSION['carrito']['productos'][$id]);
        return true;
    } else{
        return false;
    }
}