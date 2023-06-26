<?php

/* 
 * Validaciones.
 */

require '../config.php';

//Faltan por añadir los valores de: color, talla.
if( isset($_POST['id'])){
    $id = $_POST['id'];
    $token = $_POST['token'];
    
    //$talla = isset($_POST['talla']) ? $_POST['talla'] : 0;
    //$color = isset($_POST['color']) ? $_POST['color'] : 0;

//Se coprobará de nuevo si el token ha sido alterado.

$token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if (trim($token) == trim($token_tmp)) {
        
        if(isset($_SESSION['carrito']['productos'][$id])){
            //Para determinar el número de productos a comprar del mismo tipo. Ej: 4 zapatos negros con id= 4.
            $_SESSION['carrito']['productos'][$id] += 1; 
        } else{
           $_SESSION['carrito']['productos'][$id] = 1; 
        }
        
        $datos['numero'] = count($_SESSION['carrito']['productos']); //Cuenta el número de id almacenados.
        $datos['ok'] = true; //Se recibe la información correctamente.
    } else{
        $datos['ok'] = false; //En caso de no recibir información.
    }
    
} else{
    $datos['ok'] = false; //En caso de no recibir información.
}

echo json_encode($datos); //Se envían datos de vuelta mediante Json.