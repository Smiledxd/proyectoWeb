// cambiar el tamaño de la imagen al ser cargada en el input file
let $foto = document.getElementById("foto");
let $imgBase64 = document.getElementById("imgBase64");
let $miniatura = document.getElementById("miniatura");
//función para comprimir la imagen
function comprimirImagen(dataUrl,formatoImg = "image/jpeg", nuevoTamanio , calidad= 50 ,destinoImagen){
  let quality = calidad / 100; // Convertir a valor entre 0 y 1
  //crear una objeto imagen
  const img = document.createElement("img");
  img.src = dataUrl;
  //cargar la imagen en memoria, analizar y cambiar sus datos
  img.onload = function() {
    nuevoTamanio = nuevoTamanio || 200; // Tamaño máximo en píxeles
    //obtener el ancho y alto natural de la imagen cargadada
    let anchoNatural = img.naturalWidth;
    let altoNatural = img.naturalHeight;
    let ratio = altoNatural / anchoNatural;
    if(anchoNatural > nuevoTamanio){
      anchoNatural = nuevoTamanio;
      altoNatural = nuevoTamanio * ratio;
    }
    let canvas = document.createElement("canvas");
    canvas.width = anchoNatural;
    canvas.height = altoNatural;
    canvas.getContext("2d").drawImage(img, 0, 0, anchoNatural, altoNatural);
    let nuevaImagen = canvas.toDataURL(formatoImg, quality);
    //crear la imagen transformada
    let imgTransformada = document.createElement("img");
    imgTransformada.src = nuevaImagen;
    //mostrar la base64
    destinoImagen.value = nuevaImagen;
    //mostrar la imagen en miniatura
    $miniatura.innerHTML = "";
    $miniatura.appendChild(imgTransformada);
  };
};

//asignar el evento change al input file
$foto.addEventListener("change", function(e) {
  const file = e.target.files[0];
  //verificar que se trate de un archivo de imagen
  if(file && file.type.startsWith("image/")) {
    const fileReader = new FileReader();
    fileReader.readAsDataURL(file);
    fileReader.addEventListener("load", (e) => {
      comprimirImagen(e.target.result, "image/jpeg", 100, 50, $imgBase64);
    });
  }else{
    //mostrar con SweetAlert2 un mensaje de error si no es una imagen
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'El archivo seleccionado no es una imagen válida.',
    });
    e.target.value = "";  // Limpiar el input file
  }
});