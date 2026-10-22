<?php
  //incluir el archivo de conexión
  require_once("conec.php");
  ob_clean();
  //ahora se debe recibir la acción
  $accion = $_REQUEST['accion'] ?? '';

  //verificar la accion a trabajar
  if($accion == "insertar"){
    //recibir los datos
    $nombre_productos = $_REQUEST['nombre_productos'] ?? '';
    $descripcion_productos = $_REQUEST['descripcion_productos'] ?? '';
    $precio_productos = $_REQUEST['precio_productos'] ?? '';
    $cantidad_productos = $_REQUEST['cantidad_productos'] ?? '';
    $imgBase64 = $_REQUEST['imgBase64'] ?? '';
    //procesando la imagen en base64
    $avatar = ["nombreArchivo" => "", "mensaje" => ""];
    if(!empty($imgBase64)){
      //incluir la libreria controlImagen.php
      include_once("controlImagen.php");
      //llamar a la función para guardar la imagen, se le dará la ruta base y la ruta donde se guardará
      $avatar = guardarImagen($imgBase64, "../imgCargadas/");
    }
    //registrar la información de los datos en la BD tabla productos
    $sql = "INSERT INTO productos (nombre_productos, descripcion_productos, precio_productos, cantidad_productos, foto_productos) VALUES(?,?,?,?,?)";
    $preparar = mysqli_prepare($conec, $sql);
    //entregar los valores a los parámetros
    mysqli_stmt_bind_param($preparar, "ssdis", $nombre_productos, $descripcion_productos, $precio_productos, $cantidad_productos, $avatar["nombreArchivo"]);
    //ejecutar la consulta
    mysqli_stmt_execute($preparar);
    //verificar si se ejecutó correctamente
    if(mysqli_stmt_affected_rows($preparar) > 0 ){
      //establecer la respuesta de éxito
      $resultado = ["ok" => true, "mensaje" => $avatar["mensaje"] . " y producto registrado correctamente"];
      echo json_encode($resultado);
    }else{
      //establecer la respuesta de error
      $resultado = ["ok" => false, "mensaje" => "Error al registrar el producto"];
      echo json_encode($resultado);
    }
    //cerrar la conexión sql
    mysqli_stmt_close($preparar);
    mysqli_close($conec);
  }
  if($accion == "actualizar"){
    $id_productos = $_REQUEST['id_productos'] ?? 0;
    $nombre_productos = $_REQUEST['nombre_productos'] ?? '';
    $descripcion_productos = $_REQUEST['descripcion_productos'] ?? '';
    $precio_productos = $_REQUEST['precio_productos'] ?? '';
    $cantidad_productos = $_REQUEST['cantidad_productos'] ?? '';
    $imgBase64 = $_REQUEST['imgBase64'] ?? '';
    // procesando la imagen en base64
    // procesando la imagen en base64
    $avatar = ["nombreArchivo" => "", "mensaje" => ""];
    if(!empty($imgBase64)){
    // incluir la librería controlImagen.php
    include_once("controlImagen.php");
    // llamar a la función para guardar la imagen, se le dará base y la ruta donde se guardará
    $avatar = guardarImagen($imgBase64, "../imgCargadas/");
    // Eliminar la imagen anterior si existe
    $sqlImagenAnterior = "SELECT foto_productos FROM productos WHERE id_productos = ?";
    $prepararImagenAnterior = mysqli_prepare($conec, $sqlImagenAnterior);
    mysqli_stmt_bind_param($prepararImagenAnterior, "i", $id_productos);
    mysqli_stmt_execute($prepararImagenAnterior);
    $resultadoImagenAnterior = mysqli_stmt_get_result($prepararImagenAnterior);
    if(mysqli_num_rows($resultadoImagenAnterior) > 0){
        $filaImagenAnterior = mysqli_fetch_assoc($resultadoImagenAnterior);
        $nombreArchivoAnterior = $filaImagenAnterior['foto_productos'];
        if(!empty($nombreArchivoAnterior)){
            $rutaArchivoAnterior = "../imgCargadas/" . $nombreArchivoAnterior;
            if(file_exists($rutaArchivoAnterior)){
                unlink($rutaArchivoAnterior); // Eliminar el archivo anterior
              }
          }
      }
    }
    //actualizar la información de los datos en la BD con la imagen si se envía  una nueva  o sin la imagen  si no se envía una nueva
    if(!empty($imgBase64)){
      $sql = "UPDATE productos SET nombre_productos = ?, descripcion_productos = ?, precio_productos = ?, cantidad_productos = ?, foto_productos = ? WHERE id_productos = ?";
    $preparar = mysqli_prepare($conec, $sql);
    mysqli_stmt_bind_param($preparar, "ssdiss", $nombre_productos, $descripcion_productos, $precio_productos, $cantidad_productos, $avatar["nombreArchivo"], $id_productos);
    }else{
      $sql = "UPDATE productos SET nombre_productos = ?, descripcion_productos = ?, precio_productos = ?, cantidad_productos = ? WHERE id_productos = ?";
      $preparar = mysqli_prepare($conec, $sql);
      mysqli_stmt_bind_param($preparar, "ssdii", $nombre_productos, $descripcion_productos, $precio_productos, $cantidad_productos, $id_productos);
    }
    mysqli_stmt_execute($preparar);
    //verificar si se ejecutó correctamente
    if(mysqli_stmt_affected_rows($preparar) > 0 ){
      //establecer la respuesta de éxito
      $resultado = ["ok" => true, "mensaje" => $avatar["mensaje"] . " y producto actualizado correctamente"];
      echo json_encode($resultado);
    }else{
      //establecer la respuesta de error
      $resultado = ["ok" => false, "mensaje" => "Error al actualizar el producto"];
      echo json_encode($resultado);
    }
    //cerrar la conexión sql
    mysqli_stmt_close($preparar);
    mysqli_close($conec);  
  }

  if($accion == "eliminar"){
    //recibir el id del producto a eliminar
    $id_productos = $_REQUEST['id_productos'] ?? 0;
    //Cambiamos el estado a 0
    $sql = "UPDATE productos SET estado_productos = 0 WHERE id_productos = ?";
    $preparar = mysqli_prepare($conec, $sql);
    mysqli_stmt_bind_param($preparar, "i", $id_productos);
    mysqli_stmt_execute($preparar);
    //verificamos si se ejecutó
    if(mysqli_stmt_affected_rows($preparar) > 0){
      $resultado = ["ok" => true, "mensaje" => "Producto eliminado correctamente"];
      echo json_encode($resultado);
    }else{
      $resultado = ["ok" => false, "mensaje" => "Error al eliminar el producto"];
      echo json_encode($resultado);
    }
    mysqli_stmt_close($preparar);
    mysqli_close($conec);
  }

  if($accion == "buscarMostrar"){
    //hacer la busqueda para obtener la cantidad de registros
    $buscar = $_REQUEST["dato"] ?? '';
    $limite = isset($_REQUEST["limite"]) ? intval($_REQUEST["limite"]) : 5;
    $inicio = isset($_REQUEST["inicio"]) ? intval($_REQUEST["inicio"]) : 0;
    //guardar la cantidad de registros
    $cantRegistros = 0;
    $sqlRegistros = "SELECT COUNT('id_productos') AS cantidad FROM productos WHERE (nombre_productos LIKE ? OR descripcion_productos LIKE ?) AND estado_productos = 1";
    $buscarParam = "%$buscar%";
    $preparar = mysqli_prepare($conec, $sqlRegistros);
    mysqli_stmt_bind_param($preparar, "ss", $buscarParam, $buscarParam);
    mysqli_stmt_execute($preparar);
    $resultado = mysqli_stmt_get_result($preparar);
    if(mysqli_num_rows($resultado) > 0){
      $fila = mysqli_fetch_assoc($resultado);
      $cantRegistros = $fila["cantidad"];
      //obtener los registros
      $sql = "SELECT * FROM productos WHERE (nombre_productos LIKE ? OR descripcion_productos LIKE ?) AND estado_productos = 1 ORDER BY id_productos DESC LIMIT ? OFFSET ?";
      $prepararProductos = mysqli_prepare($conec, $sql);
      mysqli_stmt_bind_param($prepararProductos, "ssii", $buscarParam, $buscarParam, $limite, $inicio);
      mysqli_stmt_execute($prepararProductos);
      $resultado = mysqli_stmt_get_result($prepararProductos);
      if(mysqli_num_rows($resultado) > 0){
        $registros = [];
        while($fila = mysqli_fetch_assoc($resultado)){
          $registros[] = $fila;
        }
        //entregar los registros al array general
        $data = ["nroRegistros" => $cantRegistros, "registros" => $registros];
        echo json_encode($data);
      }else{
        //no se encontraron registros
        $data = ["nroRegistros" => $cantRegistros, "registros" => []];
        echo json_encode($data);
      }
    }else{
      $data = ["nroRegistros" => $cantRegistros, "registros" => []];
      echo json_encode($data);
    }
    //cerrar la conexión
    mysqli_stmt_close($preparar);
    if(isset($prepararProductos)){
      mysqli_stmt_close($prepararProductos);
    }
    mysqli_close($conec);
  }
?>
