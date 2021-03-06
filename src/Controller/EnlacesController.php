<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Enlaces Controller
 *
 * @property \App\Model\Table\EnlacesTable $Enlaces
 *
 * @method \App\Model\Entity\Enlace[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EnlacesController extends AppController
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
        $ssid = $this->request->getQuery('ssid');
        $channelWidth = $this->request->getQuery('channel_width');
        $itemsPerPage = $this->request->getQuery('itemsPerPage');
        
        $this->paginate = [
            'limit' => $itemsPerPage
        ];
        
        $query = $this->Enlaces->find()->order(['Enlaces.id']);
        $query->where(['Enlaces.estado_id' => 1]);
        
        if ($ssid) {
            $query->where(['Enlaces.ssid like' => '%' . $ssid . '%']);
        }
        
        if ($channelWidth) {
            $query->where(['Enlaces.channel_width' => $channelWidth]);
        }
        
        $count = $query->count();
        $enlaces = $this->paginate($query);
        $paginate = $this->request->getParam('paging')['Enlaces'];
        $pagination = [
            'totalItems' => $paginate['count'],
            'itemsPerPage' =>  $paginate['perPage']
        ];
        
        $this->set(compact('enlaces', 'pagination', 'count'));
        $this->set('_serialize', ['enlaces', 'pagination', 'count']);
    }

    /**
     * View method
     *
     * @param string|null $id Enlace id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $enlace = $this->Enlaces->get($id, [
            'contain' => ['Antenas']
        ]);

        $this->set(compact('enlace'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $enlace = $this->Enlaces->newEntity();
        if ($this->request->is('post')) {
            $enlace = $this->Enlaces->patchEntity($enlace, $this->request->getData());
            if ($this->Enlaces->save($enlace)) {
                $message = 'El enlace fue registrado correctamente';
            } else {
                $message = 'El enlace no fue registrado correctamente';
                $errors = $enlace->getErrors();
            }
        }
        $this->set(compact('enlace', 'message', 'errors'));
        $this->set('_serialize', ['enlace', 'message', 'errors']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Enlace id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $enlace = $this->Enlaces->get($id);
        if ($this->request->is('put')) {
            $enlace = $this->Enlaces->patchEntity($enlace, $this->request->getData());
            if ($this->Enlaces->save($enlace)) {
                $message = 'El enlace fue modificado correctamente';
            } else {
                $message = 'El enlace no fue modificado correctamente';
                $errors = $enlace->getErrors();
            }
        }
        $this->set(compact('enlace', 'message', 'errors'));
        $this->set('_serialize', ['enlace', 'message', 'errors']);
    }

    /**
     * Enable method
     *
     * @param string|null $id Enlace id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function enable($id = null) {
        $this->request->allowMethod(['post']);
        $enlace = $this->Enlaces->get($id);
        $enlace->estado_id = 1;
        if ($this->Enlaces->save($enlace)) {
            $message = 'El enlace fue habilitado correctamente';
        } else {
            $message = 'El enlace no fue habilitado correctamente';
            $errors = $enlace->getErrors();
        }

        $this->set(compact('enlace', 'message', 'errors'));
        $this->set('_serialize', ['enlace', 'message', 'errors']);
    }
    
    /**
     * Disable method
     *
     * @param string|null $id Enlace id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function disable($id = null) {
        $this->request->allowMethod(['post']);
        $enlace = $this->Enlaces->get($id);
        $enlace->estado_id = 2;
        if ($this->Enlaces->save($enlace)) {
            $message = 'El enlace fue deshabilitado correctamente';
        } else {
            $message = 'El enlace no fue deshabilitado correctamente';
            $errors = $enlace->getErrors();
        }

        $this->set(compact('enlace', 'message', 'errors'));
        $this->set('_serialize', ['enlace', 'message', 'errors']);
    }
}
