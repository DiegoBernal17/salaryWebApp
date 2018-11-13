<?php
/**
 * Esto se encarga de registrar un nuevo empleado
 */
// Requiere sesión
$settings['logged'] = true;
require_once '../init.php';

// Recibe parámetros necesarios
$Employees->name = $_POST['name'];
$Employees->surnames = $_POST['surnames'];
$Employees->id_card = $_POST['id_card'];
$Employees->workstation = $_POST['workstation'];
$Employees->salary_by_hour = $_POST['salary_by_hour'];


// Verifica que lo recibido no esté nada vacio
if(
  !empty($Employees->name) &&
  !empty($Employees->surnames) &&
  !empty($Employees->id_card) &&
  !empty($Employees->workstation) &&
  !empty($Employees->salary_by_hour)
) 
{
  // Registra el empleado
  $Employees->registerEmployee();
  // Crea una sesión 'status' y asigna el mensaje correspondiente
  $_SESSION['status'] = "Se ha registrado correctamente";
  // Redirige a la páginade registrar empleado para mostrar el mensaje
  header('Location: '.URL.'register_employee.php');
} else {
  // Crea el mensaje de sesión 'status_error' para indicar el error
  $_SESSION['status_error'] = "Uno o más datos vacíos";
  // Redirige a la pagina de registrar el empleado para mostrar el mensaje
  header('Location: '.URL.'register_employee.php');
}
?>