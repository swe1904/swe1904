<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_slip_item_section".
 *
 * @property int $id
 * @property string $name
 *
 * @property SlipItem[] $slipItems
 */
class SlipItemSection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_slip_item_section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[SlipItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSlipItems()
    {
        return $this->hasMany(SlipItem::class, ['section_id' => 'id']);
    }
}
