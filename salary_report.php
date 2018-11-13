<?php
// require estar logeado
$settings['logged'] = true;
// id de la pagina
$settings['page_id'] = 'salary_report';
// titulo de la pagina
$settings['title'] = "Reportes salariales";
// importar cabecera html
require 'components/header.php';
// importar submenu
require 'components/submenu.php';
?>

<!-- Caja blanca con un tamaño mayor -->
<div class="box" style="width: 782px">
  <div class="headerBox">Reportes salariales</div>
  <?php
  // llama al metodo para mostrar la tabla de salarios
  $Employees->showSalaryTable(); ?>
</div>
  
<?php
//  importar footer
require 'components/footer.php';
?>