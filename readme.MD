# Aplicación de Búsqueda de Recursos Educativos

Esta aplicación permite buscar coincidencias en los nombres de clases y exámenes de una plataforma de cursos online de idiomas a partir de las primeras letras proporcionadas (mínimo 3).

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Extensión PDO de PHP habilitada

## Instalación

1. Clone este repositorio en su máquina local:
   ```
   git clone [URL_DEL_REPOSITORIO]
   ```

2. No es necesario importar manualmente el esquema de la base de datos, ya que la aplicación configura automáticamente la base de datos durante la configuración inicial. Sin embargo, si lo desea, puede hacerlo manualmente:
   ```
   mysql -u [USUARIO] -p [NOMBRE_BASE_DATOS] < sql/schema.sql
   ```
   
   O puede ejecutar las migraciones directamente con:
   ```
   php main.php migrate
   ```

3. Configure sus credenciales de base de datos (elija uno de estos métodos):
   
   a) **Usando variables de entorno (RECOMENDADO por seguridad)**:
      - Ejecute el script de configuración de entorno:
        ```
        php setup-env.php
        ```
      - Esto creará un archivo `.env` con sus credenciales
      - **IMPORTANTE**: La aplicación siempre priorizará las credenciales del archivo `.env` si existe
   
   b) **Usando el archivo de configuración** (alternativa):
      - Edite el archivo `config/config.php` manualmente con sus credenciales.
      - O ejecute el script de configuración inicial:
        ```
        php public/index.php config
        ```
      - Nota: Si existe un archivo `.env`, sus valores tendrán prioridad sobre `config.php`

## Estructura del Proyecto

El proyecto sigue una arquitectura MVC (Modelo-Vista-Controlador):

- **Models**: Contiene la lógica de negocio y acceso a datos.
- **Views**: Gestiona la presentación de los datos.
- **Controllers**: Coordina la interacción entre los modelos y las vistas.

Además, se implementan interfaces para garantizar la consistencia y se utilizan clases abstractas para compartir funcionalidad común.

## Uso

### Comandos Disponibles

La aplicación ofrece los siguientes comandos:

```
php main.php search [TÉRMINO]     - Buscar recursos por término
php main.php config               - Configurar la aplicación
php main.php migrate              - Ejecutar migraciones de base de datos
php main.php migrate:reset        - Reiniciar la base de datos (¡elimina todos los datos!)
```

### Búsqueda de Recursos

Para buscar recursos, ejecute el comando:

```
php main.php search [TÉRMINO_DE_BÚSQUEDA]
```

Ejemplo:
```
php main.php search trabajo
```

Esto mostrará todas las clases y exámenes cuyo nombre contenga la palabra "trabajo".

### Configuración y Migración Automática

Al ejecutar la configuración inicial (ya sea mediante el archivo `.env` o `config.php`), la aplicación automáticamente:

1. Crea la base de datos si no existe
2. Crea las tablas necesarias
3. Inserta datos de ejemplo

Esto significa que después de la configuración, la aplicación está lista para usarse sin pasos adicionales.

## Patrones de Diseño Implementados

- **MVC**: Separa la lógica de negocio, presentación y control.
- **Repository Pattern**: Para el acceso a datos.
- **Strategy Pattern**: Para los diferentes tipos de búsqueda.

## Seguridad

La aplicación implementa las siguientes medidas de seguridad:

### Gestión de Credenciales

- **Variables de entorno**: Las credenciales se almacenan preferentemente en un archivo `.env` que no debe incluirse en el control de versiones.
- **Priorización de configuración**: El sistema prioriza las variables del archivo `.env` sobre las constantes de `config.php`.
- **EnvLoader**: Implementación personalizada para cargar y gestionar variables de entorno de forma segura.

### Seguridad de Base de Datos

- **Prepared Statements**: Todas las consultas SQL utilizan prepared statements para prevenir inyecciones SQL.
- **PDO**: Se utiliza PDO con opciones de seguridad mejoradas.
- **Gestión de errores**: Los errores de conexión se gestionan adecuadamente sin exponer información sensible en producción.

### Mejores Prácticas

- **Archivos de configuración separados**: Las configuraciones sensibles están en archivos separados.
- **Directrices de seguridad**: El archivo `.env.example` muestra la estructura pero no incluye credenciales reales.
- **Documentación**: Se proporcionan instrucciones claras sobre cómo configurar la aplicación de forma segura.

## Tests Unitarios

Para ejecutar los tests unitarios (requiere PHPUnit):

```
phpunit tests/
```

## Estándares de Código

Este proyecto sigue los estándares PSR:
- PSR-1: Basic Coding Standard
- PSR-2: Coding Style Guide
- PSR-4: Autoloading Standard
- Documentación con PHPDoc