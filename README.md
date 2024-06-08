# Backend de CarGram

#### Como inicializar la API

Clonar codigo del repositorio.

Genera una nueva clave para tu aplicación Laravel.

*php artisan key:generate*

#### Migrar y Poblar la Base de Datos

Ejecuta las migraciones de base de datos para crear las tablas necesarias y luego ejecuta los seeders para poblar la base de datos con datos iniciales.

**Migrar la base de datos:**

*php artisan migrate*

**Poblar la base de datos:**

El archivo SQL exportado está disponible en el directorio `/database/seeders` con el nombre `railway.sql`. Para importar la base de datos desde tu servidor local (Laragon, XAMPP, etc.), ejecuta el siguiente comando:

*php artisan db:seed --class=InitDB*

#### Iniciar el Servidor de Desarrollo

Inicia el servidor de desarrollo de Laravel.

*php artisan serve*

Esto iniciará el servidor en `http://localhost:8000`.

#### Configurar .env en local

Es importante tener en cuenta que el .env no se sube al repositorio por lo cual es importante crearlo, este podría ser un ejemplo de configuración:

*APP_NAME=Laravel*

*APP_ENV=local*

*APP_KEY=base64:gFd6CF2g33Cc12TrKou2TtIedlJJvAiMZDcSbz1g1B0=*

*APP_DEBUG=true*

*APP_URL=http://localhost*

*LOG_CHANNEL=stack*

*LOG_DEPRECATIONS_CHANNEL=null*

*LOG_LEVEL=debug*

*DB_CONNECTION=mysql*

*DB_HOST=127.0.0.1*

*DB_PORT=3306*

*DB_DATABASE=cargram*

*DB_USERNAME=root*

*DB_PASSWORD=*

#### Importación de Base de Datos

Para inicializar la base de datos con una población de datos inicial, siga los pasos a continuación:

1. **Ubicación del Archivo SQL:**
   El archivo SQL exportado está disponible en el directorio `/database/seeders` con el nombre `railway.sql`. Este archivo contiene una población de datos inicial para su conveniencia.
2. **Importación desde el Servidor Local:**
   Para importar la base de datos en su servidor local (Laragon, XAMPP, etc.), ejecute el siguiente comando en la consola:

   php artisan db:seed --class=InitDB

   Este comando ejecutará el seeder `InitDB` y poblará la base de datos con los datos especificados en el archivo `railway.sql`.

### Documentación de la API de Laravel

#### Rutas Públicas

##### Registro de Usuario

**Ruta:** `POST /api/register`

* **Descripción:** Registra a un nuevo usuario.
* **Parámetros de la solicitud:**
  * `name`: Nombre del usuario.
  * `email`: Correo electrónico del usuario.
  * `password`: Contraseña del usuario.
  * `cPassword`: Confirmación de la contraseña.
* **Respuesta exitosa:** Devuelve un mensaje de éxito, un token JWT y los datos del usuario registrado.

##### Inicio de Sesión

**Ruta:** `POST /api/login`

* **Descripción:** Inicia sesión y devuelve un token JWT.
* **Parámetros de la solicitud:**
  * `email`: Correo electrónico del usuario.
  * `password`: Contraseña del usuario.
* **Respuesta exitosa:** Devuelve un mensaje de éxito, un token JWT y los datos del usuario autenticado.

#### Rutas Protegidas (Requieren Autenticación)

##### Cerrar Sesión

**Ruta:** `POST /api/logout`

* **Descripción:** Cierra la sesión del usuario autenticado.
* **Headers:** Requiere el token de autenticación (Bearer token).
* **Respuesta exitosa:** Devuelve un mensaje indicando que la sesión se ha cerrado con éxito.

##### Obtener Conversaciones

**Ruta:** `GET /api/conversations`

* **Descripción:** Obtiene todas las conversaciones del usuario autenticado.
* **Headers:** Requiere el token de autenticación (Bearer token).
* **Respuesta exitosa:** Devuelve una lista de conversaciones, cada una con los detalles del último mensaje.

##### Crear Nueva Publicación

**Ruta:** `POST /api/newpost`

* **Descripción:** Crea una nueva publicación.
* **Headers:** Requiere el token de autenticación (Bearer token).
* **Parámetros de la solicitud:**
  * `title`: Título de la publicación.
  * `content`: Contenido de la publicación.
* **Respuesta exitosa:** Devuelve un mensaje de éxito y los detalles de la publicación creada.

##### Seguir a un Usuario

**Ruta:** `POST /api/follow`

* **Descripción:** Sigue a un usuario específico.
* **Headers:** Requiere el token de autenticación (Bearer token).
* **Parámetros de la solicitud:**
  * `user_id`: ID del usuario a seguir.
* **Respuesta exitosa:** Devuelve un mensaje indicando que ahora se sigue al usuario.

##### Obtener Feed de Publicaciones

**Ruta:** `GET /api/feed`

* **Descripción:** Obtiene las publicaciones del feed del usuario autenticado.
* **Headers:** Requiere el token de autenticación (Bearer token).
* **Respuesta exitosa:** Devuelve una lista de publicaciones del feed del usuario.

##### Obtener Usuarios

**Ruta:** `GET /api/users`

* **Descripción:** Obtiene la lista de todos los usuarios.
* **Headers:** Requiere el token de autenticación (Bearer token).
* **Respuesta exitosa:** Devuelve una lista de usuarios con sus detalles.

#### Otros Endpoints

1. **Subir Foto de Perfil:**
   * **Ruta:** `POST /api/upload-profile-photo`
   * **Descripción:** Sube o actualiza la foto de perfil del usuario.
   * **Headers:** Requiere el token de autenticación (Bearer token).
2. **Dar "Me Gusta" a una Publicación:**
   * **Ruta:** `POST /api/posts/{post}/like`
   * **Descripción:** Da "me gusta" a una publicación específica.
   * **Headers:** Requiere el token de autenticación (Bearer token).
3. **Comentar en una Publicación:**
   * **Ruta:** `POST /api/posts/{post}/comment`
   * **Descripción:** Añade un comentario a una publicación específica.
   * **Headers:** Requiere el token de autenticación (Bearer token).
4. **Obtener Publicaciones del Mapa:**
   * **Ruta:** `GET /api/feed/mapa`
   * **Descripción:** Obtiene publicaciones geolocalizadas para mostrar en un mapa.
   * **Headers:** Requiere el token de autenticación (Bearer token).
5. **Buscar Usuarios:**
   * **Ruta:** `GET /api/users/search`
   * **Descripción:** Busca usuarios por nombre o email.
   * **Headers:** Requiere el token de autenticación (Bearer token).
6. **Verificar Seguimiento:**
   * **Ruta:** `POST /api/check-following`
   * **Descripción:** Verifica si el usuario autenticado sigue a otro usuario específico.
   * **Headers:** Requiere el token de autenticación (Bearer token).
