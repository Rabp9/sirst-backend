<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * TSwitches Controller
 *
 * @property \App\Model\Table\TSwitchesTable $TSwitches
 *
 * @method \App\Model\Entity\TSwitch[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TSwitchesController extends AppController
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
        $modeloDescripcion = $this->request->getQuery('modeloDescripcion');
        $puntoText = $this->request->getQuery('puntoText');
        $ip = $this->request->getQuery('ip');
        $itemsPerPage = $this->request->getQuery('itemsPerPage');
        
        $this->paginate = [
            'limit' => $itemsPerPage
        ];
        
        
        $query = $this->TSwitches->find()
            ->contain(['Modelos', 'Puntos'])->order(['TSwitches.id']);;
        $query->where(['TSwitches.estado_id' => 1]);
        
        if ($modeloDescripcion) {
            $query->where(['Modelos.descripcion LIKE' => '%' . $modeloDescripcion . '%']);
        }
        
        if ($puntoText) {
            $query->where(['OR' =>  [
                'Puntos.descripcion LIKE' => '%' . $puntoText . '%',
                'Puntos.codigo LIKE' => '%' . $puntoText . '%'
            ]]);
        }
        
        if ($ip) {
            $query->where(['TSwitches.ip LIKE' => '%' . $ip . '%']);
        }
        
        $count = $query->count();
        $tSwitches = $this->paginate($query);
        $paginate = $this->request->getParam('paging')['TSwitches'];
        $pagination = [
            'totalItems' => $paginate['count'],
            'itemsPerPage' =>  $paginate['perPage']
        ];
        
        $this->set(compact('tSwitches', 'pagination', 'count'));
        $this->set('_serialize', ['tSwitches', 'pagination', 'count']);
    }

    /**
     * View method
     *
     * @param string|null $id T Switch id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $tSwitch = $this->TSwitches->get($id, [
            'contain' => ['Modelos', 'Puntos']
        ]);

        $this->set(compact('tSwitch'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $tSwitch = $this->TSwitches->newEntity();
        if ($this->request->is('post')) {
            $tSwitch = $this->TSwitches->patchEntity($tSwitch, $this->request->getData());
            if ($this->TSwitches->save($tSwitch)) {
                $message = 'El switch fue registrado correctamente';
            } else {
                $message = 'El switch no fue registrado correctamente';
                $errors = $tSwitch->getErrors();
            }
        }
        $this->set(compact('tSwitch', 'message', 'errors'));
        $this->set('_serialize', ['tSwitch', 'message', 'errors']);
    }

    /**
     * Edit method
     *
     * @param string|null $id T Switch id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $tSwitch = $this->TSwitches->get($id);
        if ($this->request->is('put')) {
            $tSwitch = $this->TSwitches->patchEntity($tSwitch, $this->request->getData());
            if ($this->TSwitches->save($tSwitch)) {
                $message = 'El switch fue modificado correctamente';
            } else {
                $message = 'El switch no fue modificado correctamente';
                $errors = $tSwitch->getErrors();
            }
        }
        $this->set(compact('tSwitch', 'message', 'errors'));
        $this->set('_serialize', ['tSwitch', 'message', 'errors']);
    }

    /**
     * Enable method
     *
     * @param string|null $id TSwitch id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function enable($id = null) {
        $this->request->allowMethod(['post']);
        $tSwitch = $this->TSwitches->get($id);
        $tSwitch->estado_id = 1;
        if ($this->TSwitches->save($tSwitch)) {
            $message = 'El switch fue habilitado correctamente';
        } else {
            $message = 'La siwtch no fue habilitado correctamente';
            $errors = $tSwitch->getErrors();
        }

        $this->set(compact('tSwitch', 'message', 'errors'));
        $this->set('_serialize', ['tSwitch', 'message', 'errors']);
    }
    
    /**
     * Disable method
     *
     * @param string|null $id TSwitch id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function disable($id = null) {
        $this->request->allowMethod(['post']);
        $tSwitch = $this->TSwitches->get($id);
        $tSwitch->estado_id = 2;
        if ($this->TSwitches->save($tSwitch)) {
            $message = 'El switch fue deshabilitado correctamente';
        } else {
            $message = 'La siwtch no fue deshabilitado correctamente';
            $errors = $tSwitch->getErrors();
        }

        $this->set(compact('tSwitch', 'message', 'errors'));
        $this->set('_serialize', ['tSwitch', 'message', 'errors']);
    }
}
