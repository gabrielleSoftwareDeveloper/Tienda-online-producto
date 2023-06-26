<?php
/*
 * Autor: Gabriel Orellana Vásquez.
 * Este archivo se conforma de un formulario y las  validaciones pertenecientes a todos los campos
 * que encontramos en el mismo.
 * 
 * Se utilizan funciones que encontraremos en config.php para validar datos como el correo electrónico 
 * o ,incluso, comprobar si el nombre de usuario ya existe o, el mismo correo, ya está registrado en la
 * bbdd.
 * 
 * Además, una vez el sistema haya comprobado error a error, en caso de darse, se incluirán en un array
 *que se manejará finalmente en una función para mostrar dichas incongruencias en un alert a juego
 * con la estética de la página que podrá eliminarse gracias a un evento de js.
 */
require 'config.php';
require 'database.php';
$db = new Database();
$con = $db->conectar();

//Validación de errores.
$errores = [];
if (!empty($_POST)) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);

    if (nulo([$nombre, $apellidos, $email, $usuario, $password, $telefono, $dni])) {
        $errores[] = "Debe rellenar todos los campos." . $nombre . $apellidos . $email . $usuario . $password . $telefono . $dni;
    }

    if (!email($email)) {
        $errores[] = "La dirección de correo no es válida.";
    }

    if (!validarPassword($password, $repassword)) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    if (usuarioExiste($usuario, $con)) {
        $errores[] = "El nombre de " . $usuario . " ya existe.";
    }

    if (emailExiste($email, $con)) {
        $errores[] = "Ya existe una cuenta con el correo electrónico " . $email . ".";
    }

    if (count($errores) == 0) {

        $id = registrarUsuarios([$nombre, $apellidos, $email, $telefono, $dni], $con);
        if ($id > 0) {
            $pas_hash = password_hash($password, PASSWORD_DEFAULT); //Cifrando el texto plano.
            $token = generarToken();
            if (!accesoUsuarios([$usuario, $password, $token, $id], $con)) {
                $errores[] = "Error al registrar el usuario";
            }
        } else {
            $errores[] = "Error al registrar el usuario";
        }
    }

}
?>

<html>

    <head>
        <title>Registro</title>
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


            .contenedor_login {
                height: 920px;
            }

        </style>
    </head>

    <body>
        <!-- Navigation-->
<?php include 'menu.php'; ?>


        <div id="container" style="background-color: rgb(242, 242, 242);">
            <div class="contenedor_login">

                <!-- Formulario para registrar una nueva cuenta.-->
                <div class="formulario_register">
                    <div id="imagen"><img src="img/assets/avatar.png" width="50px" height="50px" alt="alt" /></div>
                    <h2>Regístrate</h2>
                    <form action="Registro.php" method="POST" autocomplete="off">

                        <div class="campo_a_rellenar">
                            <span class="icon">
                                <ion-icon name="person"></ion-icon>
                            </span>
                            <input id="nombre" type="text" name="nombre" required>
                            <label>Nombre*</label>
                        </div>

                        <div class="campo_a_rellenar">
                            <span class="icon">
                                <ion-icon name="pencil"></ion-icon>
                            </span>
                            <input id="apellidos" type="text" name="apellidos" required>
                            <label>Apellidos*</label>
                        </div>

                        <div class="campo_a_rellenar">
                            <span class="icon">
                                <ion-icon name="location"></ion-icon>
                            </span>
                            <input id="usuario" type="text" name="usuario" required>
                            <label>Usuario*</label>
                        </div>

                        <div class="campo_a_rellenar">
                            <span class="icon">
                                <ion-icon name="mail"></ion-icon>
                            </span>
                            <input id="email" type="text" name="email" required>
                            <label>Email*</label>
                        </div>

                        <div class="campo_a_rellenar">
                            <span class="icon">
                                <ion-icon name="lock-closed"></ion-icon>
                            </span>
                            <input id="password" type="password" name="password" required>
                            <label>Contraseña*</label>
                        </div>

                        <div class="campo_a_rellenar">
                            <span class="icon">
                                <ion-icon name="lock-closed"></ion-icon>
                            </span>
                            <input id="repassword" type="password" name="repassword" required>
                            <label>Reintroduzca su contraseña*</label>
                        </div>

                        <div class="campo_a_rellenar">
                            <span class="icon">
                                <ion-icon name="albums"></ion-icon>
                            </span>
                            <input id="dni" type="text" name="dni" required>
                            <label>N.I.F./C.I.F.*</label>
                        </div>

                        <div class="campo_a_rellenar">
                            <span class="icon">
                                <ion-icon name="call"></ion-icon>
                            </span>
                            <input id="telefono" type="number" name="telefono" required>
                            <label>Teléfono*</label>
                        </div>


                        <input id="boton_enviar" value="Registrar" type="submit">
                        <div class="login-register">
                            <p>¿Ya estás dado de alta? <a href="inicio_sesion.php" class="login-link">Inicia sesión</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de alertas de formulario incompletgo o erróneo.-->
        <?php mostrarMensajes($errores); ?>

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