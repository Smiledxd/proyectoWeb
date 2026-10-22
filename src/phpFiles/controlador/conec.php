<?php
// Preparando la conexión con la BD
$conec = mysqli_connect("localhost", "root", "", "moda");

// Verificar si hubo error en la conexión
if (mysqli_connect_errno()) {
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(["ok" => false, "mensaje" => "Error al conectar a la base de datos: " . mysqli_connect_error()]);
  exit();
} else {
  // Configurar el sistema de caracteres a UTF-8
  mysqli_set_charset($conec, "utf8");
}
?>
