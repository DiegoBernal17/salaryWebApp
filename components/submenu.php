<!-- Menu -->
<header>
  <nav>
  <!-- Icono y link para el boton regresar -->
    <a href="dashboard.php"><img src="resources/icons/return.png">Regresar</a>
  <!-- Iconos y reloj para la hora-->
    <a href="#"><img src="resources/icons/register_hours.png"><?php echo $Employees->getDay(); ?></a>
  </nav>
</header>
<!-- El submenu -->
<div class="boxSubMenu">
  <div class="itemSubMenu itemSelect">
    <?php echo $Employees->getLastWeek(); ?>
  </div>
  <?php
  // si id de la pagina es register_hours o register_employee entonces muestra esto
  if($settings['page_id'] == "register_hours" || $settings['page_id'] == 'register_employee') { ?>
  <!-- Botón de nueva semana, al hacer click llama al metodo JS de agregar seman -->
  <div class="itemSubMenu" onclick="addNewWeek();">
    Nueva semana
  </div>
  <?php } ?>
</div>