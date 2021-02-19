<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;

/**
 * Payments Model
 *
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $Members
 * @property \App\Model\Table\SchedulesTable&\Cake\ORM\Association\BelongsTo $Schedules
 * @property \App\Model\Table\CreditcardsTable&\Cake\ORM\Association\BelongsTo $Creditcards
 *
 * @method \App\Model\Entity\Payment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Payment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Payment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Payment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Payment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Payment findOrCreate($search, callable $callback = null, $options = [])
 */
class PaymentsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('payments');
        $this->setDisplayField('member_id');
        $this->setPrimaryKey(['member_id', 'schedule_id', 'column_number', 'record_number']);

        $this->belongsTo('SeatReservations', [
            'foreignKey' => ['member_id', 'schedule_id', 'column_number', 'record_number'],
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('ReservationDetails', [
            'foreignKey' => ['member_id', 'schedule_id', 'column_number', 'record_number'],
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Points', [
            'foreignKey' => ['member_id', 'schedule_id', 'column_number', 'record_number'],
        ]);
        $this->belongsTo('Members', [
            'foreignKey' => 'member_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Schedules', [
            'foreignKey' => 'schedule_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Creditcards', [
            'foreignKey' => 'creditcard_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->scalar('column_number')
            ->maxLength('column_number', 2)
            ->allowEmptyString('column_number', null, 'create');

        $validator
            ->scalar('record_number')
            ->maxLength('record_number', 2)
            ->allowEmptyString('record_number', null, 'create');

        $validator
            ->integer('purchase_price')
            ->requirePresence('purchase_price', 'create')
            ->notEmptyString('purchase_price');

        $validator
            ->boolean('is_cancelled')
            ->notEmptyString('is_cancelled');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->requirePresence('updated_at', 'create')
            ->notEmptyDateTime('updated_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['member_id'], 'Members'));
        $rules->add($rules->existsIn(['schedule_id'], 'Schedules'));
        $rules->add($rules->existsIn(['creditcard_id'], 'Creditcards'));

        return $rules;
    }
    public function insert($entity)
    { //saveの仕様が「レコードが存在する場合はupdateする」なので一意でない時はfalseを返すよう変更
        $columnNumber = $entity['column_number'];
        $recordNumber = $entity['record_number'];
        if ($this->exists(['column_number' => $columnNumber, 'record_number' => $recordNumber])) {
            return false;
        }
        return $this->save($entity);
    }
    public function findReservedLists(Query $query, array $options)
    {
        $memberId = $options['memberId'];
        $today = Time::now();

        return $query
            ->contain('Schedules', function ($q) {
                return $q->contain('Movies');
            })
            ->contain('ReservationDetails')
            ->select([
                'Schedules.start_date',
                'ReservationDetails.discount_id',
                'Movies.picture_name',
                'Movies.name',
                'Movies.screening_time',
                'schedule_id',
                'column_number',
                'record_number',
                'purchase_price',
            ])
            ->where([
                'Payments.member_id' => $memberId,
                'Payments.is_cancelled' => 0,
                'Schedules.start_date >' => $today,
                'Schedules.is_deleted' => 0
            ])
            ->order([
                'Schedules.start_date' => 'DESC',
                'Payments.schedule_id' => 'ASC',
                'Payments.column_number' => 'ASC',
                'Payments.record_number' => 'ASC',
            ])
            ->toArray();
    }

    public function findApplyEntity(Query $query, array $options)
    {
        $mainKey = $options['mainKey'];

        return $query
            ->where($mainKey)
            ->select([
                'member_id',
                'schedule_id',
                'column_number',
                'record_number',
                'is_cancelled',
            ])
            ->toArray();
    }
}
