<?php

namespace Database\Factories;

use App\Models\Estado;
use App\Models\Municipio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Municipio>
 */
class MunicipioFactory extends Factory
{
    protected $model = Municipio::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cvegeo' => function (array $attributes) {
                return $attributes['cve_ent'] . $attributes['cve_mun'];
            },
            'cve_ent' => function () {
                return Estado::factory()->create()->cve_ent;
            },
            'cve_mun' => $this->faker->unique()->numerify('###'),
            'nomgeo' => $this->faker->city(),
            'cve_cab' => $this->faker->numerify('####'),
            'nom_cab' => $this->faker->citySuffix(),
            'pob_total' => $this->faker->numberBetween(10000, 1000000),
            'pob_femenina' => $this->faker->numberBetween(5000, 500000),
            'pob_masculina' => $this->faker->numberBetween(5000, 500000),
            'total_viviendas_habitadas' => $this->faker->numberBetween(1000, 200000),
        ];
    }
}
