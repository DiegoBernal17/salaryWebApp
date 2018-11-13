<?php
// titulo de la página
$settings['title'] = "Inicia sesión";
// id de la página
$settings['page_id'] = 'index';
// esta pagina no se necesita estar logeado
$settings['logged'] = false;
// importar la cabecera de la pagina
require 'components/header.php';
// si está logeado entonces va a redirigir al dashboard
if(isset($_SESSION['logged'])) {
  header('Location: '.URL.'dashboard.php');
}
?>

<!-- Donde se mostrará para logearse -->
  <div class="loginCenter">
   <!-- Formulario de inicio de sesión -->
    <form action="actions/login.php" method="POST">
      <h2>Inicia sesión</h2>
      <!-- En caso que haya un error esto se mostrará -->
      <div class="error">
      <?php
        // Si la página recibe un error muestra lo siguiente
        if(isset($_GET['error'])) {
          switch($_GET['error']) {
            case 'empty':
              echo "Uno o más campos vacíos";
            break;
            case 'email':
              echo "El email no existe";
            break;
            case 'password':
              echo "Contraseña incorrecta";
            break;
          }
        }
      ?>
      </div>
      <input type="email" name="email" placeholder="Correo" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <input type="submit" value="Ingresar" class="btnGreen">
      <hr>
      <a href="register.php">Registrarse</a>
    </form>
  </div>
<?php
// importar footer html de la página
require 'components/footer.php';
?>