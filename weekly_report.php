<?php
// se requiere estar logeado
$settings['logged'] = true;
// id de la págnia
$settings['page_id'] = 'weekly_report';
// titulo de la pagina
$settings['title'] = "Reportes semanales";
// importar cabecera html
require 'components/header.php';
// importar submenu
require 'components/submenu.php';
?>

<!-- caja blanca central -->
<div class="box" style="width: 528px">
  <div class="headerBox">Reportes semanales</div>
  <?php 
  // Muestra la tabla de reporte semanal
  $Employees->showTable(); ?>
</div>
  
<?php
// importar footer
require 'components/footer.php';
?>