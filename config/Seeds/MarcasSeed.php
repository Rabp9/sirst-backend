<?php
use Migrations\AbstractSeed;

/**
 * Marcas seed.
 */
class MarcasSeed extends AbstractSeed
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
                'descripcion' => $faker->text(60),
                'estado_id' => 1
            ];
        }
        
        $table = $this->table('marcas');
        $table->insert($data)->save();
    }
}
