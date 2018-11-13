<?php
$settings['logged'] = true;
$settings['page_id'] = 'register_employee';
$settings['title'] = "Registro de empleados";
require 'components/header.php';
require 'components/submenu.php';
?>
<div class="box">
  <div class="headerBox">Registro de empleados</div>
  <?php 
  if(isset($_SESSION['status_error'])) {
    echo '<div class="error">'.$_SESSION['status_error'].'</div>';
    unset($_SESSION['status_error']);
  }
  if(isset($_SESSION['status'])) {
    echo '<div class="success">'.$_SESSION['status'].'</div>';
    unset($_SESSION['status']);
  }
  ?>
  <form action="actions/register_employee.php" method="POST" class="dataForm">
    <input type="text" name="name" placeholder="Nombre(s)" required>
    <input type="text" name="surnames" placeholder="Apellidos" required>
    <input type="text" name="id_card" placeholder="Cédula" required>
    <input type="text" name="workstation" placeholder="Puesto" required>
    <input type="number" name="salary_by_hour" placeholder="Salario por hora" required>
    <input type="submit" value="Registrar" class="btnGreen">
  </form>
</div>
  
<?php
require 'components/footer.php';
?>