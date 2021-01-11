<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ReservationDetail Entity
 *
 * @property int $member_id
 * @property int $schedule_id
 * @property string $column_number
 * @property string $record_number
 * @property int $fee_id
 * @property int $discount_id
 * @property bool $is_cancelled
 * @property \Cake\I18n\Time $created_at
 * @property \Cake\I18n\Time $updated_at
 *
 * @property \App\Model\Entity\Member $member
 * @property \App\Model\Entity\Schedule $schedule
 * @property \App\Model\Entity\Fee $fee
 * @property \App\Model\Entity\Discount $discount
 */
class ReservationDetail extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'fee_id' => true,
        'discount_id' => true,
        'is_cancelled' => true,
        'created_at' => true,
        'updated_at' => true,
        'member' => true,
        'schedule' => true,
        'fee' => true,
        'discount' => true,
    ];
}