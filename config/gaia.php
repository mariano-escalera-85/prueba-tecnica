<?php

return [
    'estados' => [
        'endpoint' => env('GAIA_ESTADOS_ENDPOINT', 'https://gaia.inegi.org.mx/wscatgeo/v2/mgee/'),
    ],
    'municipios' => [
        'endpoint' => env('GAIA_MUNICIPIOS_ENDPOINT', 'https://gaia.inegi.org.mx/wscatgeo/v2/mgem/{cve_ent}')
    ],
];
