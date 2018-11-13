<?php
/**
 * Para iniciar sesión
 */
// No requiere estar conectado porque aquí apenas se logeará
$settings['logged'] = false;
require '../init.php';

// Recibe parámetros POST correo y contraseña
$Users->email = $_POST['email'];
$Users->password = $_POST['password'];

// Checa si el correo existe
if($Users->existEmail()) {
  // Si existe entonces ahora checa que la contraseña dada conincida con la de la base de datos
  if($Users->checkPassword()) {
    // Llama al método para inicar sesión
    $Users->login();
    // Redirige al dashboard
    header('Location: '.URL.'dashboard.php');
  } else 
  header('Location: '.URL.'index.php?error=password');
} else
header('Location: '.URL.'index.php?error=email');

?>