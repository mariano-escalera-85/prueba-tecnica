<?php

namespace App\Actions;

use App\Models\Estado;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreEstadosAction
{
    use AsAction;

    public string $commandSignature = 'app:store-estados';

    public string $commandDescription = 'Actualiza los estados obtenidos desde el `Servicio Web del Catálogo Único de Claves Geoestadísticas del INEGI`.';

    public function handle()
    {
        $estados = GetEstadosAction::run();

        $estados
            ->dtoOrFail()
            ->each(fn ($estadoData)
                => Estado::updateOrCreate(
                    $estadoData->only(['cve_ent', 'nomgeo']),
                    $estadoData->except(['cve_ent', 'nomgeo'])
                )
            );
    }

    public function asCommand(Command $command): void
    {
        $estados = $this->handle();

        $command->info('Stored!');
    }
}
