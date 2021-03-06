<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

/**
 * Enlaces Model
 *
 * @property \App\Model\Table\AntenasTable|\Cake\ORM\Association\HasMany $Antenas
 *
 * @method \App\Model\Entity\Enlace get($primaryKey, $options = [])
 * @method \App\Model\Entity\Enlace newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Enlace[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Enlace|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Enlace saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Enlace patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Enlace[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Enlace findOrCreate($search, callable $callback = null, $options = [])
 */
class EnlacesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('enlaces');
        $this->setDisplayField('ssid');
        $this->setPrimaryKey('id');

        $this->hasMany('Antenas')
            ->setForeignKey('enlace_id');
                
        $this->belongsTo('Estados')
            ->setForeignKey('estado_id')
            ->setJoinType('INNER');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('ssid')
            ->maxLength('ssid', 10)
            ->requirePresence('ssid', 'create')
            ->notEmptyString('ssid');

        $validator
            ->scalar('channel_width')
            ->maxLength('channel_width', 8)
            ->requirePresence('channel_width', 'create')
            ->notEmptyString('channel_width');

        return $validator;
    }
    
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->existsIn(['estado_id'], 'Estados'));
        // $rules->add($rules->isUnique(['ssid'], 'Ya existe un enlace con el mismo ssid'));
        $rules->add(
            function ($entity, $options) {
                if ($entity->id == null) {
                    $count = $this->find()->where(['ssid' => $entity->ssid, 'estado_id' => 1])->count();
                } else {
                    $count = $this->find()->where(['ssid' => $entity->ssid, 'estado_id' => 1, 'id !=' => $entity->id])->count();
                }
                
                if ($count == 0) {
                    return true;
                } else {
                    return false;
                }
            },
            'ssidUnique',
            [
                'errorField' => 'ssid',
                'message' => 'Ya existe un enlace activo con el mismo ssid'
            ]
        );
        return $rules;
    }
}
