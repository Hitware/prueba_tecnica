# Aplicación de Búsqueda de Recursos Educativos

Esta aplicación permite buscar clases y exámenes en una plataforma de cursos de idiomas escribiendo al menos las tres primeras letras del nombre del recurso que busca.

## Lo que necesitas antes de empezar

- PHP 7.4 o superior
- MySQL 5.7 o superior

## Cómo instalar y configurar

### Paso 1: Configura la base de datos

Para configurar la aplicación con tu base de datos:

1. Abre una terminal (línea de comandos) en la carpeta del proyecto
2. Ejecuta el siguiente comando:
   
   php setup-env.php
   
3. Se te pedirá que ingreses:
   - Nombre de usuario de MySQL
   - Contraseña de MySQL
   - Nombre para la base de datos
   - Host de la base de datos (generalmente "localhost")

### Paso 2: Crea las tablas y datos iniciales

Una vez configurada la conexión a la base de datos, ejecuta:
```
php main.php migrate
```

Este comando:
- Crea automáticamente la base de datos (si no existe)
- Crea las tablas necesarias
- Inserta datos de ejemplo

¡Después de estos dos pasos, la aplicación está lista para usarse!

## Cómo usar la aplicación

### Búsqueda de clases y exámenes

Para buscar recursos, usa el comando:
```
php main.php search [PALABRA]
```

Donde `[PALABRA]` debe tener al menos 3 letras.

#### Ejemplo:
```
php main.php search trabajo
```

Los resultados mostrarán:
- Para clases: nombre y calificación (sobre 5 puntos)
- Para exámenes: nombre y tipo de examen

#### Ejemplo de resultados:
```
Clase: Vocabulario sobre Trabajo en Inglés | 5/5
Clase: Conversaciones de Trabajo en Inglés | 5/5
Examen: Trabajos y ocupaciones en Inglés | Selección