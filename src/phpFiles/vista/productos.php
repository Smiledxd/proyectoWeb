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
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <!-- uso de bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/dashboard.css" />
    <!-- incorporar Jquery y SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-4.0.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Dashboard</title>
  </head>
  <body>
    <!-- incluir con PHP a dashboardSlider -->
    <?php
      include("dashboardSlider.php");
    ?>
    <div class="content">
      <h2>Administración de los datos</h2>
      <div class="row" style="overflow-y: auto; height: 80vh;">
        <div class="col-12 col-md-4 col-lg-3">
          <h2>Mantenimiento de Productos</h2>
          <!-- formulario -->
          <form id="formulario" autocomplete="off">
            <div class="mb-3">
              <input type="text" id="id_productos" name="id_productos" value="0">
              <input type="text" id="accion" name="accion" value="insertar">
              <label for="nombre_productos" class="form-label">Nombre del producto</label>
              <input type="text" class="form-control" id="nombre_productos" name="nombre_productos" required>
            </div>
            <div class="mb-3">
              <label for="descripcion_productos" class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcion_productos" name="descripcion_productos" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="precio_productos" class="form-label">Precio</label>
              <input type="number" step="0.01" class="form-control" id="precio_productos" name="precio_productos" required>
            </div>
            <div class="mb-3">
              <label for="cantidad_productos" class="form-label">Stock del producto</label>
              <input type="number" class="form-control" id="cantidad_productos" name="cantidad_productos" required>
            </div>
            <div class="mb-3">
              <label for="foto_productos" class="form-label">Imagen del producto</label>
              <input type="file" class="form-control" id="foto" name="fotos" accept=".jpg, .jpeg, .png, .gif, .webp">
              <textarea class="form-control" id="imgBase64" name="imgBase64" rows="1" readonly></textarea>
              <!-- mostrar imagen en miniatura -->
              <div id="miniatura" style="margin-top: 10px;"></div>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Producto
              <!-- spinner -->
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;" id="spinner"></span></button>
            <!-- cancelar registro o cambios -->
            <button type="button" class="btn btn-secondary" id="btnCancelar">Cancelar</button>
          </form>

        </div>
        <div class="col-12 col-md-8 col-lg-9">

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
    <script src="../js/productos.js"></script>
    <script src="../js/controlImagen.js"></script>
  </body>
</html>
