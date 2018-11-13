<?php
/**
 * Cerrar la sesión
 */
// Requiere estar logeado para poder cerrar sesión
$settings['logged'] = true;
require ('../init.php');
// Limpia y destruye la sesión
unset($_SESSION);
session_destroy();
// Redirige al index
header('Location: '.URL.'index.php');
?>