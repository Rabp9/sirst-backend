<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Marcas Controller
 *
 * @property \App\Model\Table\MarcasTable $Marcas
 *
 * @method \App\Model\Entity\Marca[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MarcasController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow();
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index() {
        $descripcion = $this->request->getQuery('descripcion');
        $itemsPerPage = $this->request->getQuery('itemsPerPage');
        
        $this->paginate = [
            'limit' => $itemsPerPage
        ];
        
        $query = $this->Marcas->find()->order(['Marcas.id']);
        $query->where(['Marcas.estado_id' => 1]);
        
        if ($descripcion) {
            $query->where(['Marcas.descripcion LIKE' => '%' . $descripcion . '%']);
        }
        
        $count = $query->count();
        $marcas = $this->paginate($query);
        $paginate = $this->request->getParam('paging')['Marcas'];
        $pagination = [
            'totalItems' => $paginate['count'],
            'itemsPerPage' =>  $paginate['perPage']
        ];
        
        $this->set(compact('marcas', 'pagination', 'count'));
        $this->set('_serialize', ['marcas', 'pagination', 'count']);
    }
    
    /**
     * View method
     *
     * @param string|null $id Marca id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $marca = $this->Marcas->get($id, [
            'contain' => ['Modelos']
        ]);

        $this->set(compact('marca'));
        $this->set('_serialize', ['marca']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $marca = $this->Marcas->newEntity();
        if ($this->request->is('post')) {
            $marca = $this->Marcas->patchEntity($marca, $this->request->getData());
            if ($this->Marcas->save($marca)) {
                $message = 'La marca fue registrada correctamente';
            } else {
                $message = 'La marca no fue registrada correctamente';
                $errors = $marca->getErrors();
            }
        }
        $this->set(compact('marca', 'message', 'errors'));
        $this->set('_serialize', ['marca', 'message', 'errors']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Marca id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $marca = $this->Marcas->get($id);
        if ($this->request->is('put')) {
            $marca = $this->Marcas->patchEntity($marca, $this->request->getData());
            if ($this->Marcas->save($marca)) {
                $message = 'La marca fue modificada correctamente';
            } else {
                $message = 'La marca no fue modificada correctamente';
                $errors = $marca->getErrors();
            }
        }
        $this->set(compact('marca', 'message', 'errors'));
        $this->set('_serialize', ['marca', 'message', 'errors']);
    }

    /**
     * Enable method
     *
     * @param string|null $id Marca id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function enable($id = null) {
        $this->request->allowMethod(['post']);
        $marca = $this->Marcas->get($id);
        $marca->estado_id = 1;
        if ($this->Marcas->save($marca)) {
            $message = 'La marca fue habilitada correctamente';
        } else {
            $message = 'La marca no fue habilitada correctamente';
            $errors = $marca->getErrors();
        }

        $this->set(compact('marca', 'message', 'errors'));
        $this->set('_serialize', ['marca', 'message', 'errors']);
    }
    
    /**
     * Disable method
     *
     * @param string|null $id Marca id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function disable($id = null) {
        $this->request->allowMethod(['post']);
        $marca = $this->Marcas->get($id);
        $marca->estado_id = 2;
        if ($this->Marcas->save($marca)) {
            $message = 'La marca fue deshabilitada correctamente';
        } else {
            $message = 'La marca no fue deshabilitada correctamente';
            $errors = $marca->getErrors();
        }

        $this->set(compact('marca', 'message', 'errors'));
        $this->set('_serialize', ['marca', 'message', 'errors']);
    }
}
