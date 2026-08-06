<?php
ob_start();
require_once __DIR__ . "/conec.php";
ob_clean();

header('Content-Type: application/json; charset=utf-8');

// Recibir los datos del formulario (soporta $_POST o $_REQUEST)
$username = isset($_REQUEST["username"]) ? trim($_REQUEST["username"]) : "";
$password = isset($_REQUEST["password"]) ? trim($_REQUEST["password"]) : "";

if (empty($username) || empty($password)) {
  echo json_encode(["ok" => false, "mensaje" => "Por favor ingresa tu usuario y contraseña."]);
  exit();
}

// Verificar la existencia del usuario activo
$sql = "SELECT * FROM usuarios WHERE nombre_usuarios = ? AND estado_usuarios = 1";
$preparar = mysqli_prepare($conec, $sql);

if ($preparar) {
  mysqli_stmt_bind_param($preparar, "s", $username);
  mysqli_stmt_execute($preparar);
  $resultado = mysqli_stmt_get_result($preparar);

  if ($resultado && mysqli_num_rows($resultado) > 0) {
    $registro = mysqli_fetch_assoc($resultado);

    // Verificar la contraseña cifrada
    if (password_verify($password, $registro["clave_usuarios"])) {
      // Iniciar sesión
      session_start();
      $_SESSION["usuario_conectado"] = $registro["nombre_usuarios"];
      $_SESSION["foto_conectado"] = isset($registro["foto_usuarios"]) ? $registro["foto_usuarios"] : null;

      echo json_encode(["ok" => true, "mensaje" => "¡Bienvenido al sistema!"]);
    } else {
      echo json_encode(["ok" => false, "mensaje" => "Contraseña incorrecta."]);
    }
  } else {
    echo json_encode(["ok" => false, "mensaje" => "Usuario no registrado o inactivo."]);
  }
  mysqli_stmt_close($preparar);
} else {
  echo json_encode(["ok" => false, "mensaje" => "Error en la consulta a la base de datos."]);
}

mysqli_close($conec);
?>
