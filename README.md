# Prueba técnica

## Laravel + MySQL + Bootstrap

### Tecnologías por utilizar

-   Laravel 6 o superior
-   Bootstrap
-   MySQL

## Instrucciones

### 1. Estados

Obtener el listado de las 32 entidades federativas utilizando el Servicio Web del Catálogo Único de Claves Geoestadísticas del INEGI.

Endpoint:

[https://gaia.inegi.org.mx/wscatgeo/v2/mgee/](https://gaia.inegi.org.mx/wscatgeo/v2/mgee/)

Guardar los estados en una tabla llamada  `estados`.

La información almacenada deberá incluir, como mínimo:

-   Clave del estado, utilizando el campo  `cve_ent`.
-   Nombre del estado, utilizando el campo  `nomgeo`.
-   Población total, utilizando el campo  `pob_total`.

Mostrar los estados en un listado, idealmente utilizando DataTables, con las siguientes columnas:

-   Clave.
-   Estado.
-   Población total.

El listado deberá contar con:

-   Paginación.
-   Búsqueda.
-   Ordenamiento.

La población total deberá mostrarse en un formato legible, incluyendo separadores de miles.

Ejemplo:

`798447`  deberá mostrarse como  `798,447`.

Evitar registros duplicados si se vuelve a ejecutar la carga; es decir, el proceso deberá ser idempotente.

### 2. Municipios por estado

Al dar clic en un estado del listado, consultar sus municipios utilizando el Servicio Web del Catálogo Único de Claves Geoestadísticas del INEGI.

Endpoint:

[https://gaia.inegi.org.mx/wscatgeo/v2/mgem/{CLAVE_ESTADO](https://gaia.inegi.org.mx/wscatgeo/v2/mgem/%7BCLAVE_ESTADO)}

La clave del estado corresponde al campo  `cve_ent`  obtenido en la consulta de entidades federativas.

Ejemplo para Aguascalientes, cuya clave es  `01`:

[https://gaia.inegi.org.mx/wscatgeo/v2/mgem/01](https://gaia.inegi.org.mx/wscatgeo/v2/mgem/01)

Mostrar todos los municipios correspondientes al estado seleccionado.

## Entrega

Responde este correo compartiendo:

-   El enlace al repositorio con el código fuente.
-   La URL de un despliegue navegable y funcional de la aplicación.

El plazo esperado para realizar la entrega es de siete días hábiles.


# Configuración del ambiente de desarrollo

- Agrega la siguiente linea a tu `/etc/hosts`
    - `127.0.0.1 enegence.local`
- Configura el certificado local:
    - `./bin/setup-ssl.sh.`
- Instala las dependencias de PHP usando un contenedor temporal
    - `docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html laravelsail/php84-composer:latest composer install --ignore-platform-reqs`
- Copy the `.env.example` to `.env`
    - `cp .env.example .env`
- Construye y arranca el ambiente de desarrollo
    - `sail up -d`
- Genera la llave de la aplicación:
    - `./vendor/bin/sail artisan key:generate`
- Instala las dependencias
    - `sail composer install`
- Ejecuta las migraciones a la base de datos
    - `sail art migrate`
- Construye los assets de frontend para desarrollo
    - `sail pnpm run dev`
- Visita la dirección configurada
    - `https://enegence.local`
