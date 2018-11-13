<?php
// se necesita estar logeado
$settings['logged'] = true;
// id de la página
$settings['page_id'] = 'register_hours';
// titulo de la página 
$settings['title'] = "Registro de horas";
// importar el header html de la página
require 'components/header.php';
// importar el submenu
require 'components/submenu.php';
?>

<!-- Caja blanca del centro -->
<div class="box">
  <!-- Cabecera de la caja -->
  <div class="headerBox">Registro de horas</div>
  <?php 
  // llama al metodo que muestra la tabla de registrar horas trabajadas del día
  $Employees->showTableHoursDay() ?>
</div>
<script>

let hourInput = document.querySelector("input");
hourInput.addEventListener("keypress", function(ev){
  if(ev.key == 'Enter') {
    addHours(hourInput);
  }
});

// habilita una caja de texto para poder editar las horas
function enable(input){
  let i = document.getElementsByName(input);
  i[0].removeAttribute("disabled")
}
</script>
<?php
// importar el footer
require 'components/footer.php';
?>