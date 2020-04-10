<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Member Entity
 *
 * @property int $id
 * @property string $membername
 * @property string $memberpass
 * @property string|null $pr
 * @property \Cake\I18n\FrozenTime|null $create_datetime
 */
class Member extends Entity
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
        'membername' => true,
        'memberpass' => true,
        'pr' => true,
        'create_datetime' => true,
    ];
}
