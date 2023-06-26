<?php

/*
 * Autor: Gabriel Orellana Vásquez.
 * El archivo d einicio de sesión contiene un pequeño formulario que recoge los datos de un usuario
 * que en principio ya debe estar registrado en nuestra BBBDD. Se comprueba siempre que esté todo
 * relleno antes de enviar los datos para comprobar con la función login.
 * 
 * Una vez los datos son procesados se comprobarán las coincidencias y en caso de que no se encuentre
 * al usuario en los registros se procederá a enviar el mensaje de error, de lo contrario,
 * se enviará al usuario a la página de inicio con la sesión ya incializada.
 * 
 * La sesión inicializada se puede comprobar cuando clicamos sobre el emoticono de usuario del menú 
 * (derecha).
 * 
 * Siempre dispondremos de la opción de dirigirnos a la pantalla de registro.
 */
require 'config.php';
require 'database.php';
$db = new Database();
$con = $db->conectar();

//Validación de errores.
$errores_inicio = [];
if (!empty($_POST)) {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (nulo([$usuario, $password])) {
        $errores_inicio[] = "Debe rellenar todos los campos.";
    }
    
    if(count($errores_inicio) == 0){
    $errores_inicio[] = login($usuario, $password, $con);
}
}


function login($usuario, $password, $con){
    $sql = $con ->prepare("select id, usuario, password, token from acceso_usuario where usuario like ? limit 1");
    $sql->bindParam(1,$usuario, PDO::PARAM_STR);
    $sql->execute(); 
    if($row = $sql->fetch(PDO::FETCH_ASSOC)){
        if(esActivo($usuario, $con)){
            
            //if(password_verify(trim($password), trim($row['token']))){ //Se valida la contraseña con su hash correspondiente.
            if($password == $row['password']){    
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['usuario'];
                header("Location:index.php");
                exit;
        } else{
            return 'El usuario no está registrado.';
        }
        }
    }
    
    return 'El usuario y/o contraseña no son correctos.';
}

function esActivo($usuario, $con){
    $sql = $con ->prepare("select activacion from acceso_usuario where usuario like ? limit 1");
    $sql->bindParam(1,$usuario, PDO::PARAM_STR);
    $sql->execute(); 
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    
            if($row['activacion'] == 1){
                return true;
            }
            return false; 
}

?>


<html>

    <head>
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
        <title>Inicio de sesión</title>
        <link rel="icon" type="image/x-icon" href="img/logotipo.png">
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/all.min.css" />
        <link rel="stylesheet" href="css/mdb.min.css" />

        <style>

            
            /*Estilos generales*/
            
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

        <div id="container" style="background-color: rgb(242, 242, 242);">
            <div class="contenedor_login">
                
                <div class="formulario">
                    <div id="imagen"><img src="img/assets/avatar.png" width="50px" height="50px" alt="alt" /></div>
                    <h2>Iniciar sesión</h2>
                    <form action="inicio_sesion.php" method="POST" autocomplete="off">
                        <div class="campo_a_rellenar">
                            <span class="icon">
                                <ion-icon name="mail"></ion-icon>
                            </span>
                            <input id="usuario_inicio" type="text" name="usuario" required>
                            <label>Usuario</label>
                        </div>
                        <div class="campo_a_rellenar">
                            <span class="icon">
                                <ion-icon name="lock-closed"></ion-icon>
                            </span>
                            <input id="password_inicio" type="password" name="password" required>
                            <label>Contraseña</label>
                        </div>

                        <input id="boton_enviar" value="Entrar" type="submit">
                        <div class="login-register">
                            <p>¿No estás dado de alta? <a href="Registro.php" class="register-link">Regístrate</a></p>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- Lista de alertas de formulario incompletgo o erróneo.-->
        <?php mostrarMensajes($errores_inicio); ?>

        <!-- Contact-->
        <?php include 'footer.php'; ?>
        
        <script>
            //Eliminar div.
            document.getElementById("closeButton").addEventListener("click", function () {
                let alertDiv = document.querySelector(".alert");
                alertDiv.remove();
            });
        </script>

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    </body>

</html>
