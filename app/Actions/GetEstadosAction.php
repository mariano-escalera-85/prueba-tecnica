<?php

namespace App\Actions;

use App\Http\Integrations\GetEstadosRequest;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;
use Saloon\Http\Response;

class GetEstadosAction
{
    use AsAction;

    public string $commandSignature = 'gaia:get-estados';

    public string $commandDescription = 'Obtiene los estados desde el `Servicio Web del Catálogo Único de Claves Geoestadísticas del INEGI`.';

    public function handle(): Response
    {
        return new GetEstadosRequest()->send();
    }

    public function asCommand(Command $command): void
    {
        $estados = $this->handle();

        $command->info('Fetched!');
    }
}
