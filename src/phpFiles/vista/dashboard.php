<?php
  //verificar si la sesión está activa
  session_start();
  if(!isset($_SESSION["usuario_conectado"]) or ($_SESSION["usuario_conectado"] == "") ){
    //expulsar al usuario a la página del login
    header("location: ../../login.html");
    exit();
  }
  //obtener los datos de las variables de sesión
  $usuario_conectado = $_SESSION["usuario_conectado"];
  $foto_conectado = $_SESSION["foto_conectado"];
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <!-- uso de bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
    <link rel="stylesheet" href="../css/dashboard.css" />
    <!-- incorporar Jquery y SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Dashboard - DesignCo</title>
  </head>
  <body>
    <!-- incluir con PHP a dashboardSlider -->
    <?php
      include("dashboardSlider.php");
    ?>
    <div class="content">
      <h2>Bienvenido al Centro de Administración MODA</h2>
      <!-- div con estilo bootstrap flex con cards autojustables -->
      <div class="d-flex flex-wrap justify-content-center">
        <!-- card -->
        <div class="card m-2" style="width: 18rem;">
          <img src="../img/usuarios.png" class="card-img-top" alt="Usuarios" />
          <div class="card-body">
            <h5 class="card-title">Usuarios</h5>
            <p class="card-text">
              Gestiona los usuarios de la plataforma, incluyendo la creación, edición y eliminación de cuentas.
            </p>
            <a href="usuarios.php" class="btn btn-primary">Ir a Usuarios</a>
          </div>
        </div>
        <!-- card -->
        <div class="card m-2" style="width: 18rem;">
          <img src="../img/productos.png" class="card-img-top" alt="Productos" />
          <div class="card-body">
            <h5 class="card-title">Productos</h5>
            <p class="card-text">
              Gestiona los productos disponibles en la plataforma, incluyendo la creación, edición y eliminación de productos.
            </p>
            <a href="productos.php" class="btn btn-primary">Ir a Productos</a>
          </div>
        </div>
        <!-- card -->
        <div class="card m-2" style="width: 18rem;">
          <img src="../img/mensajes.png" class="card-img-top" alt="Mensajes" />
          <div class="card-body">
            <h5 class="card-title">Mensajes</h5>
            <p class="card-text">
              Gestiona los mensajes de la plataforma, incluyendo la creación, edición y eliminación de mensajes.
            </p>
            <a href="mensajes.php" class="btn btn-primary">Ir a Mensajes</a>
          </div>
        </div>
        <!-- card -->
        <div class="card m-2" style="width: 18rem;">
          <img src="../img/configuracion.png" class="card-img-top" alt="Configuración" />
          <div class="card-body">
            <h5 class="card-title">Configuración</h5>
            <p class="card-text">
              Gestiona la configuración de la plataforma, incluyendo la creación, edición y eliminación de configuraciones.
            </p>
            <a href="configuracion.php" class="btn btn-primary">Ir a Configuración</a>
          </div>
        </div>
      </div>
    </div>
    <script>
      let btn = document.querySelector("#btn");
      let slider = document.querySelector(".slider");

      btn.addEventListener("click", () => {
        slider.classList.toggle("active");
      });
    </script>
  </body>
</html>
