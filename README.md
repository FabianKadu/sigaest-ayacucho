# SIGAEST - Sistema Académico para la Gestión de Información

## Descripción
SIGAEST es un sistema académico diseñado para gestionar la información académica y administrativa de manera eficiente. Este repositorio contiene el código fuente, la base de datos, y las instrucciones necesarias para instalar, configurar y desplegar el sistema en entornos locales y de producción.

## Requisitos Previos
Antes de comenzar, asegúrate de cumplir con los siguientes requisitos:

* **PHP 8.0 o superior**: Descargar PHP.
* **Composer**: Descargar Composer.
* **Servidor Apache o XAMPP**: Para configurar el entorno de desarrollo.
* **Extensiones de PHP necesarias**:
  * SOAP
  * SODIUM
  * PDO_MYSQL
  * FILEINFO

## Instalación y Configuración

### 1. Clonar el repositorio
```bash
git clone https://github.com/Jduchman/sigaest_dev.git
cd sigaest_dev
```

### 2. Instalar dependencias
Ejecuta el siguiente comando para instalar las dependencias necesarias:
```bash
composer install
```

### 3. Configurar la base de datos
1. **Crear la base de datos**:
   * Abre tu cliente MySQL (como phpMyAdmin) y crea una nueva base de datos.
2. **Importar el esquema**:
   * Ve a la base de datos creada y utiliza la opción "Importar" para cargar el archivo `backup.sql`.
3. **Editar configuración de conexión**:
   * En el archivo `tabla/conexion.php`, configura los parámetros de la base de datos:
```php
$host = "localhost";
$db = "nombre_base_datos";
$user_db = "usuario";
$pass_db = "contraseña";
```

### 4. Configurar librerías
Extrae el archivo `librerias.zip` en la raíz del proyecto.

### 5. Levantar el servidor local
1. Copia los archivos del proyecto a la carpeta `htdocs` de XAMPP.
2. Accede a la URL en tu navegador: `http://localhost/sigaest_dev/`.

## Despliegue en Producción

### Requisitos técnicos del hosting
* PHP 8.0 o superior
* Extensiones requeridas: SOAP, SODIUM, PDO_MYSQL, FILEINFO.

### Pasos para el despliegue
1. **Preparar el proyecto**:
   * Comprime el código fuente en un archivo `.TAR`.
   * Prepara el esquema de base de datos en formato `.sql`.
2. **Subir al hosting**:
   * Usa el gestor de archivos del panel de control para subir y descomprimir el proyecto.
3. **Configurar la base de datos**:
   * Importa el archivo `.sql` en una nueva base de datos creada.
   * Configura los datos de conexión directamente en el archivo de configuración del proyecto.

## Guía de Uso
1. Accede al sistema desde tu navegador:
   * **Entorno local**: `http://localhost/sigaest_dev/`.
   * **Producción**: URL configurada en tu dominio.
2. Ingresa con las credenciales de administrador demo para iniciar.

## Tecnologías Utilizadas
* PHP 8.0
* MySQL
* Composer
* Dependencias:
  * `firebase/php-jwt`: Manejo de tokens JWT.
  * `illuminate/database`: ORM Eloquent de Laravel.
  * `gabordemooij/redbean`: ORM ligero.
  * `nikic/fast-route`: Enrutador eficiente.

## Contribuciones
Las contribuciones son bienvenidas. Crea un fork del repositorio, realiza tus cambios y envía un pull request.

## Licencia
Este proyecto está bajo la licencia MIT.
