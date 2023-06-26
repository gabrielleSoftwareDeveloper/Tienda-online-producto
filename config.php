<?php

/* 
 * Autor: Gabriel Orellana Vásquez
 * Es uno de los archivos que se utilizarán globalmente junto a database.php
 * En este caso se define la moneda del euro para utilizar en el resto del código y
 * los tokens para cifrar la información que recorrerá el catálogo y la página de producto.
 * 
 * Además, se definen también datos correspondientes a la conexión con PayPal, utilizando también
 * el euro como moneda principal.
 * 
 * Por último, además del inicio de las variables de sesión, encontramos diferentes funciones pertenecientes
 * a las validaciones del registro de usuarios y acceso del usuario ya registrado.
 * Se comprueban tanto si existen ciertos datos como el formato o si se deja algún campo vacío que deba rellenarse.
 * 
 */

define("KEY_TOKEN", "APR.wqc-354*"); //Password para cifrar información.
define("MONEDA", "€"); //Moneda utilizada en el resto de la web.

//Datos correspondientes a PayPal
define("CLIENT_ID", "AbENXdZfJM_uaXbgApbkbZEotnDWze3m10pZ5LbuMWxR4xlTlW4SCmpbel5xdSF1p7JCeIy1liUD0tcY");
define("CURRENCY", "EUR");

session_start(); //Se inicia sesión. Se mantendrá para todas las sessions empleadas.

$num_cart = 0; //Variable no utilizada en el diseño final.
/*Su función era actualizarse para emplearse de forma visible en el carrito indicando el número
*de productos guardados en dicho carrito.*/
if(isset($_SESSION['carrito']['productos'])){ //Si existe se contará y devolverá cuantos tiene.
    $num_cart = count($_SESSION['carrito']['productos']);
}


//Funciones para el registro de usuarios.

function nulo(array $datos){
    foreach ($datos as $dato){
        
        if(strlen(trim($dato)) < 1){
            return true;
    }
}
return false;
}

function email($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}

function validarPassword($password, $repassword){
    
    if(strcmp($password, $repassword) === 0){

    return true;
}

return false;
}

function usuarioExiste($usuario, $con){
    $sql = $con->prepare("select id from acceso_usuario where usuario like ? limit 1");
    $sql-> execute([$usuario]);
    if($sql -> fetchColumn()> 0){
        return true;
    }
    return false;
}

function emailExiste($email, $con){
    $sql = $con->prepare("select id from usuarios where email like ? limit 1");
    $sql->bindParam(1, $email, PDO::PARAM_STR);
    $sql->execute();
    if($sql -> fetchColumn() > 0){
        return true;
    }
    return false;
}

function mostrarMensajes(array $errores){
    if(count($errores) > 0){
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert" style="background-color: rgb(242, 242, 242); color: #333;"><ul>';
       
        foreach($errores as $error){
           echo '<li>'.$error.'</li>'; 
        }
        
        echo '</ul>';
        echo '<button id="closeButton" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

function generarToken(){
    return md5(uniqid(mt_rand(), false)); 
//Identificador aleatorio para que, si se solicita a la vez varios tokens, se generen diferentes.
}

function registrarUsuarios(array $datos, $con){
    $sql = $con ->prepare("insert into usuarios(nombre, apellidos, email, telefono, estatus, fecha_alta, dni)"
            . "values (?,?,?,?,1,now(),?)");
    
   $sql->bindParam(1, $datos[0] ,PDO::PARAM_STR);
   $sql->bindParam(2, $datos[1] ,PDO::PARAM_STR);
   $sql->bindParam(3, $datos[2] ,PDO::PARAM_STR);
   $sql->bindParam(4, $datos[3] ,PDO::PARAM_STR);
   $sql->bindParam(5, $datos[4] ,PDO::PARAM_STR);

       if($sql ->execute()){
        return $con -> lastInsertId();
    }
      return 0;  
    
}

function accesoUsuarios(array $datos, $con){

    $sql = $con->prepare("insert into acceso_usuario(usuario, password, activacion, token, id_cliente) "
            . "values(?, ?, 1, ?, ?)");
    
   $sql->bindParam(1, $datos[0] ,PDO::PARAM_STR);
   $sql->bindParam(2, $datos[1] ,PDO::PARAM_STR);
   $sql->bindParam(3, $datos[2] ,PDO::PARAM_STR);
   $sql->bindParam(4, $datos[3] ,PDO::PARAM_INT);

    if($sql ->execute()){
       return true; 
    }
    return false;
    
}

?>