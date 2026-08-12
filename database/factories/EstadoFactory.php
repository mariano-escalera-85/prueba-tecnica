<?php

namespace Database\Factories;

use App\Models\Estado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Estado>
 */
class EstadoFactory extends Factory
{
    protected $model = Estado::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cveEnt = $this->faker->unique()->numerify('##');
        
        return [
            'cvegeo' => $cveEnt,
            'cve_ent' => $cveEnt,
            'nomgeo' => $this->faker->state(),
            'nom_abrev' => $this->faker->stateAbbr(),
            'pob_total' => $this->faker->numberBetween(100000, 10000000),
            'pob_femenina' => $this->faker->numberBetween(50000, 5000000),
            'pob_masculina' => $this->faker->numberBetween(50000, 5000000),
            'total_viviendas_habitadas' => $this->faker->numberBetween(10000, 2000000),
        ];
    }
}
