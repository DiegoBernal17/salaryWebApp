<?php
// Esto sirve para poder guardar valores de sesión
session_start();
// VARIABLES GLOBABLES
// Obtiene la ruta completa donde se encuentra este archivo
define('PATH', dirname ( realpath ( __FILE__ )));
// Se define la url del sitio web (SE PUEDE MODIFICAR)
define('URL', "http://localhost:8080/appWebSalarios/");

// Verifica si está definida la variable de sesión 'id'
if(isset($_SESSION['id'])) {
  // Si es así entonces crea una variable global USER_ID guardando el valor de la sesion
  define('USER_ID', $_SESSION['id']);
}

// Importar archivos
require 'vendor/autoload.php';
require_once 'classes/users.php';
require_once 'classes/employees.php';

// Crear objetos de los archivos importados
$Users = new Users\Users();
$Employees = new Employees\Employees();

// Si no está definida la variable de sesion 'logged' 
// y la pagina en la que se encutra requiere que haya iniciado sesión
if(!isset($_SESSION['logged']) && $settings['logged']) {
  // entonces redirige al index para que inicie sesión
  header('Location: index.php');
}

// Llama al método calculate que sirve para calcular los totales de los empleados
$Employees->calculate();
?>