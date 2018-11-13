<?php
// esta página requiere estar logeado
$settings['logged'] = true;
// el id de la página
$settings['page_id'] = 'dashboard';
// el titulo que se mostrará al ingresar a esta página
$settings['title'] = "Dashboard page";
// importar la cabecera html de la página, el head
require 'components/header.php';
?>
<!-- Menú de arriba del dashboard -->
<header>
  <nav>
    <a href="dashboard.php"><img src="resources/icons/dashboard.png">Inicio</a>
    <a href="register_employee.php"><img src="resources/icons/register_employee.png">Registro empleados</a>
    <a href="register_hours.php"><img src="resources/icons/register_hours.png">Registro horas</a>
    <a href="weekly_report.php"><img src="resources/icons/weekly_report.png">Reporte semanal</a>
    <a href="salary_report.php"><img src="resources/icons/salary_report.png">Reporte salarial</a>
  </nav>
</header>

<!-- Caja blanca -->
<div class="box">
  <!-- Mensaje de bienvenida -->
  <h2>Bienvenido <?php echo $Users->getName(); ?></h2>
</div>
  
<?php
// importar el footer html de la paginas
require 'components/footer.php';
?>