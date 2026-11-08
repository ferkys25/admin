# Sistema de Gestión y Generación de Horarios (UDLA)
## Módulo de Administración Académica

Este proyecto es la primera fase del sistema web para la gestión de horarios académicos de la Universidad de Las Américas (UDLA). Su función es registrar, validar y organizar la información de profesores, materias, períodos y paralelos antes de ejecutar el algoritmo de generación de horarios.

## Requisitos del Sistema
Para correr el proyecto en tu computadora necesitas:
* Laragon (o cualquier entorno con PHP 8.2 o superior y MySQL)
* Composer instalado
* Git

## Cómo instalar y correr el proyecto en tu computadora (Laragon)

1. Clona el repositorio dentro de tu carpeta de proyectos (por ejemplo en C:\laragon\www\Admin).

2. Abre una terminal en la carpeta del proyecto e instala las librerías con Composer:
   composer install

3. Crea tu archivo de configuración .env copiando el archivo de ejemplo:
   cp .env.example .env

4. Genera la clave de la aplicación:
   php artisan key:generate

5. Configura tu base de datos en el archivo .env. Si usas MySQL en Laragon pon los siguientes datos:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=admin
   DB_USERNAME=root
   DB_PASSWORD=

6. Crea las tablas en la base de datos y llénalas con los datos iniciales de prueba:
   php artisan migrate:fresh --seed

7. Inicia el servidor local:
   php artisan serve

8. Abre tu navegador e ingresa a: http://localhost:8000

## Usuarios y credenciales de prueba

El sistema viene con tres usuarios precargados para probar los permisos de cada rol:

* Administrador
  Correo: admin@udla.edu.ec
  Clave: password123
  Acceso: Puede ver el panel principal y tiene acceso completo a crear, editar y eliminar profesores, materias, períodos y paralelos.

* Profesor
  Correo: profesor@udla.edu.ec
  Clave: password123
  Acceso: Solo lectura. Solo puede ver su horario asignado. Las rutas de administración están bloqueadas.

* Estudiante
  Correo: estudiante@udla.edu.ec
  Clave: password123
  Acceso: Solo lectura. Puede ver las materias que cursa y la cuadrícula semanal. No puede entrar a la administración.

En la pantalla de inicio de sesión hay botones de acceso rápido que rellenan el correo y la contraseña automáticamente para no tener que escribirlos.

## Características principales del proyecto

1. Control de acceso por Roles (Middleware)
El middleware CheckAdminRole protege todas las rutas que empiezan con /admin. Si un estudiante o un profesor intenta ingresar a una ruta administrativa escribiendo la dirección en el navegador, el sistema lo bloquea y lo manda a su pantalla de Mi Horario con un mensaje de advertencia.

2. Validación de Cédula Ecuatoriana (Dato Sensible en el Servidor)
La cédula de los profesores se valida matemáticamente en el backend antes de guardar en la base de datos:
* Revisa que tenga exactamente 10 dígitos numéricos.
* Revisa que el código de provincia esté entre el 01 y el 24.
* Revisa que el tercer dígito sea menor a 6.
* Aplica el algoritmo del Módulo 10 multiplicando los dígitos por los factores 2 y 1 alternados para comprobar el dígito verificador.
* Comprueba que la cédula no esté repetida.

Cédulas para probar en el formulario:
* Válida: 1710034065 (la guarda sin problemas)
* Falsa: 1710034060 (la rechaza mostrando el error en pantalla)

3. Selección dependiente de materias y profesores (Fetch API)
En el formulario de paralelos, el campo de profesores permanece bloqueado hasta que el usuario elige una materia. Al elegirla, JavaScript hace una consulta en segundo plano al servidor y llena la lista únicamente con los docentes que están capacitados para dictar esa materia según la tabla profesor_materia.

## Cómo subir el proyecto a Render.com (con Docker y PostgreSQL)

El proyecto incluye un archivo Dockerfile listo para desplegar en Render con base de datos PostgreSQL:

1. Sube tu código a un repositorio en GitHub (git push).

2. Entra a render.com y crea una base de datos PostgreSQL:
   * Haz clic en New + y elige PostgreSQL.
   * Selecciona el plan gratuito (Free) y haz clic en Create Database.
   * Copia el valor que dice Internal Database URL.

3. Crea el Web Service:
   * Haz clic en New + y elige Web Service.
   * Conecta tu repositorio de GitHub. Render detectará automáticamente el Dockerfile.
   * Selecciona el plan Free.

4. Agrega las variables de entorno en Render:
   * APP_NAME = UDLA Horarios
   * APP_ENV = production
   * APP_KEY = base64:6Z/8OLElgKgNDaDa3AjBi6g7Xd1zk/VRW29DOtSQ25o=
   * DB_CONNECTION = pgsql
   * DB_URL = pega aquí la Internal Database URL que copiaste en el paso 2

5. Haz clic en Create Web Service. Docker se encargará de instalar las dependencias, conectar a PostgreSQL, ejecutar las migraciones y levantar el sitio web.
