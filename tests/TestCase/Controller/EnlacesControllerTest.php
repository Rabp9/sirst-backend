<?php
namespace App\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\EnlacesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\EnlacesController Test Case
 */
class EnlacesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Enlaces',
        'app.Antenas'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex() {
        $this->get('/enlaces.json');
        $this->assertResponseContains('"totalItems": 6');
        
        $this->get('/enlaces.json?ssid=11');
        $this->assertResponseContains('"totalItems": 3');
        
        $this->get('/enlaces.json?channel_width=40MHZ');
        $this->assertResponseContains('"totalItems": 3');
        
        $this->get('/enlaces.json?ssid=11&channel_width=40MHZ');
        $this->assertResponseContains('"totalItems": 2');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd() {
        $data = [
            'ssid' => 'TM_24_35',
            'channel_width' => '40MHZ '
        ];
        $this->post('/enlaces.json', $data);
        $this->assertResponseCode(200);
        
        $marcas = TableRegistry::getTableLocator()->get('Enlaces');
        $query = $marcas->find()->where(['ssid' => $data['ssid']]);
        $this->assertEquals(1, $query->count());
        
        $this->assertResponseContains('"message": "El enlace fue registrado correctamente"');
    }
}
