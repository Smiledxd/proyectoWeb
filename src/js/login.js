// Verificar que el documento HTML está completamente cargado
$(document).ready(function () {
  // Interceptar el envío del formulario mediante AJAX
  $("#loginForm").on("submit", function (e) {
    e.preventDefault(); // Evitar la recarga de la página

    $.ajax({
      beforeSend: function () {
        // Mostrar el spinner de carga y deshabilitar botón
        $("#spinner").show();
        $("#loginButton").prop("disabled", true);
      },
      method: "POST",
      url: "phpFiles/controlador/controlarLogin.php",
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
    })
      .done(function (respuesta) {
        console.log("Respuesta servidor:", respuesta);
        
        let resp = respuesta;
        if (typeof respuesta === "string") {
          try {
            resp = JSON.parse(respuesta);
          } catch (e) {
            console.error("Error parseando JSON:", e, respuesta);
            Swal.fire({
              icon: "error",
              title: "Error del Servidor",
              text: "Respuesta no válida recibida del servidor.",
            });
            return;
          }
        }

        if (resp.ok) {
          // Mostrar mensaje de bienvenida con SweetAlert2 y redirigir al Dashboard
          Swal.fire({
            icon: "success",
            title: "¡Bienvenido!",
            text: resp.mensaje,
            timer: 1200,
            showConfirmButton: false,
          }).then(function () {
            window.location.href = "phpFiles/vista/dashboard.php";
          });
        } else {
          // Mostrar mensaje de error con SweetAlert2
          Swal.fire({
            icon: "error",
            title: "Error de Acceso",
            text: resp.mensaje,
          });
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.error("Error en la petición AJAX:", textStatus, errorThrown);
        Swal.fire({
          icon: "error",
          title: "Error de Conexión",
          text: "No se pudo conectar con el servidor PHP. Revisa tu conexión a MySQL.",
        });
      })
      .always(function () {
        // Ocultar el spinner y rehabilitar botón
        setTimeout(function () {
          $("#spinner").hide();
          $("#loginButton").prop("disabled", false);
        }, 500);
      });
  });
});
