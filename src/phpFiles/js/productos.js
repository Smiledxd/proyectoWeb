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
        //entregar el numero de registros de la paginación
        crearPaginacion(resp.nroRegistros, limite);
        //generar las filas de la tabla
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
  $("#cuerpoTablaProductos").on("click", ".editar", function(e){
    e.preventDefault();
    // obtener el id del registro
    let id = $(this).data("id");
    // preguntar si se desea editar los datos
    Swal.fire({
        title: '¿Desea editar los datos del producto?',
        text: "Se cargarán los datos en el formulario",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, editar',
        cancelButtonText: 'No, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // mostrar los datos en el formulario
            $("#id_productos").val(registros[id].id_productos);
            $("#nombre_productos").val(registros[id].nombre_productos);
            $("#descripcion_productos").val(registros[id].descripcion_productos);
            $("#precio_productos").val(registros[id].precio_productos);
            $("#cantidad_productos").val(registros[id].cantidad_productos);
            // cambiar el texto del botón guardar
            $("#btn_guardar").html('Actualizar Producto <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;" id="spinner"></span>');
            // cambiar la acción del formulario a actualizar
            $("#accion").val("actualizar");
        }
    });
  });
  
  //funcion para crear la pagination utilizando la variable nroPaginacion
  function crearPaginacion(totalRegistros, limite){
    let totalPaginas = Math.ceil(totalRegistros / limite);
    let paginacion = "";
    //la pagination debe tener un boton ir al inicio, un boton ir al final, y los botones numericos deben tener un limite de 5 botones numericos. Además el botón activo debe estar resaltado
    paginacion += `<li class="page-item ${nroPaginacion == 0 ? 'disabled' : ''}">
      <a class="page-link" href="#" data-inicio="0">Inicio</a>
    </li>`;
    for (let i = 0 ; i < totalPaginas; i++) {
      let inicio = i * limite;
      paginacion += `<li class="page-item ${nroPaginacion == inicio ? 'active' : ''}">
        <a class="page-link" href="#" data-inicio="${inicio}">${i + 1}</a>
      </li>`;
    }
    paginacion += `<li class="page-item ${nroPaginacion >= (totalPaginas - 1) * limite ? 'disabled' : ''}">
      <a class="page-link" href="#" data-inicio="${(totalPaginas - 1) * limite}">>></a>
    </li>`;
    $("#paginacionProductos").html(paginacion);
  }
  //accion para la paginación
  $("#paginacionProductos").on("click", ".page-link", function(e){
    e.preventDefault();
    //actualizar el numero de paginacion con el valor del boton clickeado
    nroPaginacion = ($(this).html()=="&lt;&lt;") ? 0 :$(this).html();
    let inicio = $(this).data("inicio");
    //obtener el valor de la búsqueda
    let dato = $("#buscar_productos").val();
    //actualizar la variable global nroPaginacion
    buscarMostrar(dato, 5, inicio);
    //activar el botón clickeado en función al valor  de la variable  nroPaginacion
    $("paginacionProductos .page-item").removeClass("active");
    //verificar que boton clickeado en función al valor de la variable nroPaginacion
    //esperar 1 segundo antes de  la activación del botón
    setTimeout(function(){
      $("paginacionProductos .page-item").each(function(){
        if($(this).html()==nroPaginacion){
          $(this).addClass("active");
        }
      });
    }, 300);
  });
  //búsqueda en tiempo real
  $("#buscar_productos").on("keyup", function(){
    let dato = $(this).val();
    buscarMostrar(dato);
  });
  //botón cancelar. Limpia el formulario
  $("#btnCancelar").on("click", function(){
    $("#formulario_productos")[0].reset();
    $("#id_productos").val("0");
    $("#accion").val("insertar");
    $("#miniatura").html("");
    $("#imgBase64").val("")
  });
  //eliminar producto con confirmación
  $(document).on("click", ".eliminar", function(e){
    e.preventDefault();
    let indice = $(this).data("id");
    let idProducto = registros[indice].id_productos;
    //preguntar antes de eliminar con SweetAlert2
    Swal.fire({
      icon: 'warning',
      title: '¿Estás seguro?',
      text: 'El producto será eliminado',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'No, cancelar'
    }).then(function(result){
      if(result.isConfirmed){
        //enviar la petición al servidor
        $.ajax({
          method: "POST",
          url: "../controlador/controlarProductos.php",
          data: {
            accion: "eliminar",
            id_productos: idProducto
          }
        })
        .done(function(respuesta){
          let resp = JSON.parse(respuesta);
          if(resp.ok){
            Swal.fire({
              icon: 'success',
              title: 'Eliminado',
              text: resp.mensaje
            });
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
        });
      }
    });
  });
});
