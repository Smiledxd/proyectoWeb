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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard.css" />
    <!-- incorporar Jquery y SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Dashboard - DaviloWeb</title>
  </head>
  <body>
    <!-- incluir con PHP a dashboardSlider -->
    <?php
      include("dashboardSlider.php");
    ?>
    <div class="content">
      <h2>Bienvenido al Dashboard</h2>
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
