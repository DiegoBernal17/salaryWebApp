<?php
$settings['title'] = "Registrarse";
$settings['page_id'] = 'register';
$settings['logged'] = false;
require 'components/header.php';
// Si está logeado redirige al dashboard
if(isset($_SESSION['logged'])) {
  header('Location: '.URL.'dashboard.php');
}
?>
<!-- Caja blanca central -->
  <div class="loginCenter">
    <h2>Registrarse</h2>
    <!-- En caso que haya un error muestra lo siguiente -->
    <div class="error">
    <?php
      if(isset($_GET['error'])) {
        switch($_GET['error']) {
          case 'empty':
            echo "Uno o más campos vacíos";
          break;
          case 'email':
            echo "El email ya existe";
          break;
          case 'password':
            echo "Las contraseñas no coinciden";
          break;
        }
      }
    ?>
    </div>
    <!-- Formulario de registro de usuario -->
    <form action="actions/register.php" method="POST">
      <input type="text" name="name" placeholder="Nombre" required>
      <input type="text" name="last_name" placeholder="Apellido" required>
      <input type="email" name="email" placeholder="Correo" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <input type="password" name="repassword" placeholder="Repite contraseña" required>
      <input type="submit" value="Registrarse" class="btnGreen">
      <hr>
      <a href="index.php">Regresar</a>
    </form>
  </div>
<?php
// importar footer
require 'components/footer.php';
?>