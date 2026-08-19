<?php
  //funcion para guardar la imagen en base64
  function guardarImagen($imgBase64, $ruta){
    //decodificar la imagen
    $base_to_php = explode(',', $imgBase64);
    //obtener el código de la imagen
    $dataImg = base64_decode($base_to_php[1]);
    //obtener el formato de la imagen
    $formato = explode(';', $base_to_php[0]);
    $formato = explode('/', $formato[0]);
    //validar solo el formato jpeg a jpg
    if($formato[1] == "jpeg"){
      $formato[1] = "jpg";  
    }
    //generar un nombre unico
    $nombreImagen = time() . "." . $formato[1];
    //establecer la ruta completa
    $rutaCompleta = $ruta . $nombreImagen;
    //guardar y verificar si se guardó correctamente
    if(file_put_contents($rutaCompleta, $dataImg)){
      //establecer la respuesta de éxito
      $respuesta = ["nombreArchivo" => $nombreImagen, "mensaje" => "Imagen guardada correctamente"];
      return $respuesta;
    }else{
      //establecer la respuesta de error
      $respuesta = ["nombreArchivo" => "", "mensaje" => "Error al guardar la imagen"];
      return $respuesta;
    }
  }
?>
