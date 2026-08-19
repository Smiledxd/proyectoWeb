//verificar que todo esté listo
$(document).ready(function(){
  //declarar variables global registros
  let registros = [];

  //trabajar con el formulario
  $("#formulario_productos").on("submit",function(event){
    event.preventDefault();
    //enviar los datos en segundo plano
    $.ajax({
      beforeSend: function(){
        //mostrar el spinner
        $("#spinner").show();
      },
      method: "POST",
      url: "../controlador/controlarProductos.php",
      data: new FormData(this),
      processData: false,
      contentType: false,
      cache: false,
    })
    .done(function(respuesta){
      console.log(respuesta);
      let resp = JSON.parse(respuesta);
      //verificar si la respuesta es true y mostramos los mensajes por sweetAlert2,además se debe limpiar el formulario
      if(resp.ok){
        Swal.fire({
          icon: 'success',
          title: 'Éxito',
          text: resp.mensaje
        });
        //limpiar el formulario
        $("#formulario_productos")[0].reset();
        $("#miniatura").html("");
        $("#btn_guardar").html('Guardar Producto <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;" id="spinner"></span>');
        //mostrar los últimos 5 registros
        buscarMostrar();
      }else{
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: resp.mensaje
        });
      }
    })
    .fail(function(error){
      console.log(error);
    })
    .always(function(){
      //esperar 1 segundo
      setTimeout(function(){
        //ocultar el spinner
        $("#spinner").hide();
      },1000);
    });
  });

  //establecer la paginación
  let nroPaginacion = 0;

  //funcion para buscar y mostrar
  function buscarMostrar(dato="", limite=5, inicio=0){
    $.ajax({
      method: "POST",
      url: "../controlador/controlarProductos.php",
      data: {
        accion: "buscarMostrar",
        dato: dato,
        limite: limite,
        inicio: inicio
      }
    })
    .done(function(respuesta){
      let resp = JSON.parse(respuesta);
      console.log(resp);
      //generar una tabla con los registros
      registros = resp.registros;
      if(registros.length > 0){
        let filas = "";
        for (let i = 0; i < registros.length; i++) {
          //obtener el nombre de la imagen
          let avatarProducto = (registros[i].foto_productos == "") ? "../img/productos.png" : "../imgCargadas/" + registros[i].foto_productos;
          filas += `<tr>
            <td>${registros[i].nombre_productos}</td>
            <td>${registros[i].descripcion_productos}</td>
            <td>${registros[i].precio_productos}</td>
            <td>${registros[i].cantidad_productos}</td>
            <td><img src="${avatarProducto}" alt="Foto de ${registros[i].nombre_productos}" width="50"></td>
            <td>
              <a href="#" class="btn btn-warning btn-sm">
                <i class="bx bxs-edit editar" data-id="${i}"></i>
              </a>
              <a href="#" class="btn btn-danger btn-sm">
                <i class="bx bxs-trash eliminar" data-id="${i}"></i>
              </a>
            </td>
          </tr>`;
        }
        $("#cuerpoTablaProductos").html(filas);
      }else{
        $("#cuerpoTablaProductos").html("<tr><td colspan='6' class='text-center'>No hay registros</td></tr>");
      }
    })
    .fail(function(error){
      console.log(error);
    });
  }

  //llamar a buscarMostrar al cargar la página
  buscarMostrar();

  //búsqueda en tiempo real
  $("#buscar_productos").on("keyup", function(){
    let dato = $(this).val();
    buscarMostrar(dato);
  });
});
