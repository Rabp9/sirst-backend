<?php
use Migrations\AbstractSeed;

/**
 * Cruces seed.
 */
class CrucesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run() {
        $faker = Faker\Factory::create();
        $data = [];
        
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'regulador_id' => $i + 1,
                'codigo' => $faker->unique()->numberBetween(1000, 9999),
                'descripcion' => $faker->text(60),
                'estado_id' => 1
            ];
        }
        
        $table = $this->table('cruces');
        $table->insert($data)->save();
    }
}
