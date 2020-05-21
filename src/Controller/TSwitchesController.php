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
        $modelo_id = $this->request->getQuery('modelo_id');
        $punto_id = $this->request->getQuery('punto_id');
        $ip = $this->request->getQuery('ip');
        $items_per_page = $this->request->getQuery('items_per_page');
        
        $this->paginate = [
            'limit' => $items_per_page
        ];
        
        $query = $this->TSwitches->find()
            ->contain(['Modelos', 'Puntos']);
        
        if ($modelo_id) {
            $query->where(['TSwitches.modelo_id' => $modelo_id]);
        }
        
        if ($punto_id) {
            $query->where(['TSwitches.punto_id' => $punto_id]);
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
                $code = 200;
                $message = 'El switch fue registrado correctamente';
            } else {
                $message = 'El switch no fue registrado correctamente';
            }
        }
        $this->set(compact('tSwitch', 'code', 'message'));
        $this->set('_serialize', ['tSwitch', 'code', 'message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id T Switch id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $tSwitch = $this->TSwitches->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tSwitch = $this->TSwitches->patchEntity($tSwitch, $this->request->getData());
            if ($this->TSwitches->save($tSwitch)) {
                $this->Flash->success(__('The t switch has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The t switch could not be saved. Please, try again.'));
        }
        $modelos = $this->TSwitches->Modelos->find('list', ['limit' => 200]);
        $puntos = $this->TSwitches->Puntos->find('list', ['limit' => 200]);
        $this->set(compact('tSwitch', 'modelos', 'puntos'));
    }

    /**
     * Enable method
     *
     * @param string|null $id Centrale id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function enable($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $centrale = $this->Centrales->get($id);
        if ($this->Centrales->delete($centrale)) {
            $this->Flash->success(__('The centrale has been deleted.'));
        } else {
            $this->Flash->error(__('The centrale could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    /**
     * Disable method
     *
     * @param string|null $id Centrale id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function disable($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $centrale = $this->Centrales->get($id);
        if ($this->Centrales->delete($centrale)) {
            $this->Flash->success(__('The centrale has been deleted.'));
        } else {
            $this->Flash->error(__('The centrale could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
