## API DE GESTION DE CANCIONES

Esta API nos proporciona un conjunto de endpoints para la gestión de canciones, así como el login de usuarios y el registro de nuevas cuentas de usuario.

Tenemos: 

# Base de datos

Es la base de datos utilizada por la API:

log: Registra eventos del sistema.
canciones: Contiene detalles de las canciones, incluyendo título, artista, género, duración e imagen.
usuarios: Guarda información sobre los usuarios registrados en el sistema.

# ENDPOINTS DE LA API

En `canciones` encontramos: 

GET /canciones: Obtiene todas las canciones disponibles. Requiere autenticación mediante token. Se puede filtrar por el ID del usuario.
POST /canciones: Crea una nueva canción. Requiere autenticación mediante token. Se deben proporcionar los datos de la canción en el cuerpo de la solicitud.
PUT /canciones/{id}: Actualiza los detalles de una canción existente. Requiere autenticación mediante token y el ID de la canción a actualizar.
DELETE /canciones/{id}: Elimina una canción existente. Requiere autenticación mediante token y el ID de la canción a eliminar.

Con usuarios: 

POST /usuarios/registro: Registra una nueva cuenta de usuario. No requiere autenticación. Se deben proporcionar los datos del usuario en el cuerpo de la solicitud.

## Clase DATABASE

Se incluye una clase Database que facilita la interacción con la base de datos. 

Esta clase proporciona métodos para realizar consultas, inserciones, actualizaciones y eliminaciones de registros.

## API
La api de api-canciones ha utilizado estas dependecas: 


MySQL: Base de datos relacional para almacenar datos.
PHP: Lenguaje de programación para el desarrollo del backend.
JWT: Para la generación y verificación de tokens de autenticación.
mysqli: Extensión de PHP para interactuar con MySQL.