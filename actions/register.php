<?php
/**
 * Este archivo se encarga de registrar un nuevo usuario para entrar al sistema
 */
$settings['logged'] = false;
require_once '../init.php';

// Recibe parámetros
$Users->name = $_POST['name'];
$Users->last_name = $_POST['last_name'];
$Users->email = $_POST['email'];
$Users->password = $_POST['password'];
$repassword = $_POST['repassword'];

// Verifica que no estén vacios los datos recibidos
if(
  !empty($Users->name) &&
  !empty($Users->last_name) &&
  !empty($Users->email) &&
  !empty($Users->password)
) 
{
  // Verifica si el correo no esté ya registrado
  if(!$Users->existEmail()) {
    // Verifica que las contraseñas introducidas coincidas
    // para evitar que se introdujo mal la contraseña
    if($Users->password == $repassword) {
      // Registra el usuario
      $Users->register();
      // Inicia sesión
      $Users->login();
      // Redirige al dashboard
      header('Location: '.URL.'dashboard.php');
    } else
    header('Location: '.URL.'register.php?error=password');
  } else
  header('Location: '.URL.'register.php?error=email');
} else
header('Location: '.URL.'register.php?error=empty');

?>