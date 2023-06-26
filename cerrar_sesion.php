<?php

/*
 * Autor: Gabriel Orellana Vásquez.
 * Archivo encargado de cerrar la sesión de usuario, tras ello se redirige a la página de inicio index.php.
 */

require 'config.php';

session_destroy();

header("Location: index.php");

