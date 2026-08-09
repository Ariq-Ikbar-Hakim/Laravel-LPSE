<?php

namespace Database\Factories;

use App\Models\Paket;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaketFactory extends Factory
{
    protected $model = Paket::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ppk_id' => null,
            'pp_id' => null,
            'kode_rup' => 'RUP-' . $this->faker->unique()->randomNumber(5),
            'nama_paket' => $this->faker->words(3, true),
            'pagu' => $this->faker->randomFloat(2, 10000000, 999999999),
            'status' => 'draft',
            'dilihat_admin_at' => null,
            'metode' => null,
            'sumber_dana' => null,
            'jenis' => null,
        ];
    }
}
