<?php
/**
 * Crea la nueva semana en la base de datos
 */
// No requiere iniciar sesión
$settings['logged'] = true;
require_once '../init.php';
// Llama al método para crear la hoja de cálculo nueva
$Employees->createSheetOfWeek();
?>