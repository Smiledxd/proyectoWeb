# WebEfectiva · Landing de servicios web + panel de administración

> **Proyecto de práctica.** Lo hice para aprender maquetación, PHP y MySQL construyendo algo parecido a un caso real: una agencia ficticia que ofrece diseño web. No es un negocio ni un cliente real.

## Qué tiene

**Sitio público (HTML, CSS y JavaScript)**
- Landing de una agencia de diseño web, con hero en video, catálogo de servicios, proceso en tres pasos, planes y recomendaciones.
- Página de inicio de sesión.

**Panel de administración (PHP + MySQL)**
- **Login** con sesiones y contraseñas verificadas con `password_verify`, contra una tabla de usuarios activos.
- **Mantenimiento de productos (CRUD)**: crear, listar, editar y dar de baja.
- **Búsqueda y paginación** con `LIKE`, `LIMIT` y `OFFSET`.
- **Subida de imágenes** desde el navegador: la imagen viaja en base64 y el servidor la guarda con un nombre único.
- **Baja lógica**: los productos no se borran, se marcan como inactivos con un campo de estado.
- Todas las consultas usan **sentencias preparadas** (`prepare` + `bind_param`) para evitar inyección SQL.

## Qué aprendí

- Separar el código en vistas y controladores dentro de `phpFiles/`.
- Comunicar el frontend con PHP mediante peticiones que devuelven JSON.
- Manejar sesiones para proteger las páginas del panel.
- Por qué se guardan contraseñas cifradas y no en texto plano.
- Por qué es mejor una baja lógica que un `DELETE` cuando los datos importan.

## Tecnologías

HTML · CSS · JavaScript · PHP · MySQL · pnpm · Oxlint

## Cómo ejecutarlo

El sitio público funciona solo:

```bash
pnpm install
pnpm dev   # http://localhost:1234
```

El panel necesita PHP y MySQL (por ejemplo, con XAMPP):

1. Copia la carpeta `src/` dentro de `htdocs/`.
2. Crea una base llamada `moda` con las tablas `usuarios` y `productos`.
3. Ajusta la conexión en `src/phpFiles/controlador/conec.php` si tu usuario de MySQL no es `root` sin contraseña.

> La demo en GitHub Pages solo muestra la parte estática: Pages no ejecuta PHP.

## Qué mejoraría hoy

- Incluir el script `.sql` para crear la base y un usuario de prueba, así cualquiera puede levantarlo.
- Validar en el servidor el tipo y el peso de las imágenes antes de guardarlas.
- Leer los datos de conexión desde variables de entorno en vez de escribirlos en el código.
- Unificar el nombre de la marca: hoy conviven WebEfectiva, DesignCo y MODA.
- Comprimir los videos del hero, que pesan bastante para una landing.
