<?php

/* 
 * Autor: Gabriel Orellana Vásquez.
 * Conexión a la BBDD de forma segura.
 */

class Database {
    private $hostname = "localhost:3307";
    private $database = "tienda_online";
    private $username = "root";
    private $password = "";
    private $charset = "utf-8";
    
    function conectar(){
        
        try{
        $conexion = "mysql:host=". $this->hostname . ";dbname=" . $this->database;
        
        /*Para lograr mayor seguridad*/
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
                ];
                
                $pdo = new PDO($conexion, $this->username, $this->password, $options);
                
                return $pdo;
    } catch (PDOException $e){
        echo 'Error de conexión'. $e->getMessage();
        exit; //Si se genera un error ya no realizará ninguna operación extra.
    }
}
}


