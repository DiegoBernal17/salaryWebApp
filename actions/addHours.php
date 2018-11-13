<?php
/**
 * Este archivo se encarga de agregar (o actualizar) 
 * las horas del día trabajadas
 */
// Cabecera http
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: false");
// Requiere estar logeado
$settings['logged'] = true;
require '../init.php';

// Recibe parámetro post
$cell = $_POST['cell'];
$hours = $_POST['hours'];

// Mientras no estén vacíos los campos recibidos
if(!empty($cell) && !empty($hours)) {
  // Actualiza las horas de la celda dada
  $Employees->updateHours($cell, $hours);
  // responde con el código 200
  http_response_code(200);
} else {
  // Respomde con error 404
  http_response_code(404);
}
?>