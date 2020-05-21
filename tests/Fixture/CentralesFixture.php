<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CentralesFixture
 */
class CentralesFixture extends TestFixture
{
    public $import = ['table' => 'centrales'];
    
    public $records = [
        [
            'descripcion' => 'Central 1 CCHH',
            'nro' => '1'
        ],
        [
            'descripcion' => 'Central 2 España',
            'nro' => '2'
        ],
        [
            'descripcion' => 'Central 3 América',
            'nro' => '3'
        ],
        [
            'descripcion' => 'Central 4 Periferia',
            'nro' => '4'
        ]
    ];
}
