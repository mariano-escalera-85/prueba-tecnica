<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Prueba Técnica') }}</title>

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body class="bg-light text-dark min-vh-100 d-flex flex-column">
        <header class="container py-3">
            @if (Route::has('login'))
                <nav class="d-flex justify-content-end gap-2">
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="container my-auto py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-4 p-md-5">
                            <h1 class="h2 fw-bold mb-4 text-center">Prueba Técnica: Laravel + MySQL + Bootstrap</h1>
                            
                            <hr class="mb-4">

                            <h3 class="h4 fw-bold text-primary">1. Instrucciones y Requerimientos</h3>
                            <p class="text-secondary">Esta aplicación fue desarrollada siguiendo los requerimientos de la prueba técnica:</p>
                            <ul class="text-secondary mb-4">
                                <li>Consumir el Servicio Web del Catálogo Único de Claves Geoestadísticas del <strong>INEGI</strong>.</li>
                                <li>Obtener y guardar en base de datos los 32 estados y sus respectivos municipios.</li>
                                <li>Mostrar la información en un listado (<strong>DataTables</strong>) con paginación, búsqueda y ordenamiento.</li>
                                <li>Formatear la población total con separadores de miles.</li>
                                <li>Evitar registros duplicados, asegurando que el proceso de carga sea <strong>idempotente</strong>.</li>
                            </ul>

                            <h3 class="h4 fw-bold text-primary">2. ¿Cómo funciona la aplicación?</h3>
                            <p class="text-secondary">
                                Para utilizar la aplicación, primero es necesario <strong>iniciar sesión</strong> o <strong>registrarse</strong>.
                                Una vez autenticado, tendrás acceso al Dashboard donde verás el Listado de Estados.
                            </p>
                            <p class="text-secondary mb-4">
                                La aplicación se comunica con la API de INEGI. Si es la primera vez que se accede, la base de datos local no tendrá la información,
                                por lo que la aplicación obtiene los datos directamente de la API de forma transparente, los guarda localmente y los muestra en la tabla.
                                Al hacer clic en un estado en el listado, se realiza un proceso similar para obtener y mostrar sus municipios.
                            </p>

                            <h3 class="h4 fw-bold text-primary mb-3">3. Implementación y Decisiones de Diseño</h3>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-white h-100">
                                        <h5 class="fw-bold"><i class="bi bi-shield-lock text-primary"></i> Autenticación</h5>
                                        <p class="mb-0 text-secondary small">
                                            Se utiliza el sistema nativo de autenticación basada en sesiones de Laravel para proteger las rutas. El dashboard y las consultas API están restringidos únicamente a usuarios autenticados.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-white h-100">
                                        <h5 class="fw-bold"><i class="bi bi-lightning-charge text-success"></i> Caché de API</h5>
                                        <p class="mb-0 text-secondary small">
                                            Las respuestas de la API están cacheadas por 1 hora (3600 segundos) mediante el plugin de caché de <strong>Saloon</strong> en conjunto con el driver de caché de Laravel, reduciendo drásticamente la latencia y la carga en el servidor externo.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-white h-100">
                                        <h5 class="fw-bold"><i class="bi bi-funnel text-info"></i> Formateo de Datos (Accessors)</h5>
                                        <p class="mb-0 text-secondary small">
                                            El formateo de la población (<code>pob_total</code>) con separadores de miles se maneja elegantemente en los modelos mediante <strong>Eloquent Accessors</strong> (<code>pobTotal(): Attribute</code>), manteniendo limpia la capa de presentación.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-white h-100">
                                        <h5 class="fw-bold"><i class="bi bi-database text-warning"></i> Sincronización DB e Integridad</h5>
                                        <p class="mb-0 text-secondary small">
                                            Los datos usan <code>updateOrCreate</code> garantizando idempotencia. Además, la tabla de municipios está configurada con <strong>Cascade on Delete</strong> en la DB, por lo que al eliminar un Estado, todos sus municipios se borran automáticamente.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="p-3 border rounded bg-white h-100">
                                        <h5 class="fw-bold"><i class="bi bi-browser-front text-danger"></i> UI y DataTables (Master-Detail)</h5>
                                        <p class="mb-0 text-secondary small">
                                            Se utiliza <code>yajra/laravel-datatables</code>. Para los municipios, se implementó un patrón Master-Detail; la tabla de municipios se carga asíncronamente y se muestra incrustada dentro de la tabla principal (child rows) al expandir un Estado.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <h3 class="h4 fw-bold text-primary mb-3">4. Manejo y Particularidades de la API (INEGI)</h3>
                            <div class="p-3 border rounded bg-white mb-2">
                                <h5 class="fw-bold"><i class="bi bi-check2-circle text-success"></i> Validación Estricta de Respuestas</h5>
                                <p class="mb-0 text-secondary small">
                                    Antes de que la respuesta de la API sea transformada en <strong>Data Transfer Objects (DTOs)</strong>, pasa por un middleware de Saloon (<code>ValidateResponseData</code>) que valida rigurosamente la estructura del JSON contra reglas definidas en el método <code>responseRules()</code> de la Request. Esto asegura que la aplicación solo procese información íntegra y predecible.
                                </p>
                            </div>
                            <div class="p-3 border rounded bg-white">
                                <h5 class="fw-bold"><i class="bi bi-exclamation-triangle text-danger"></i> Resolución de Inconsistencias HTTP</h5>
                                <p class="mb-0 text-secondary small">
                                    La API de municipios presenta una particularidad: al no encontrar datos, devuelve un estado HTTP <code>200 OK</code> pero con un código <code>404</code> dentro del cuerpo de la respuesta (en el campo <code>result</code>). Para manejar esto de manera estándar, la clase <code>GetMunicipiosRequest</code> sobrescribe el método <code>hasRequestFailed()</code> de Saloon, inspeccionando el JSON interno. Si detecta el error 404, dispara una <code>NotFoundException</code>, permitiendo que la aplicación lo maneje como un verdadero fallo HTTP.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
