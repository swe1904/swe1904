<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_slip_item".
 *
 * @property int $id
 * @property int $slip_id
 * @property string|null $description
 * @property int|null $value
 * @property int $section_id
 * @property string|null $notes
 *
 * @property SlipItemSection $section
 * @property Slip $slip
 */
class SlipItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_slip_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slip_id', 'section_id'], 'required'],
            [['slip_id', 'value', 'section_id'], 'integer'],
            [['description', 'notes'], 'string', 'max' => 255],
            [['slip_id'], 'exist', 'skipOnError' => true, 'targetClass' => Slip::class, 'targetAttribute' => ['slip_id' => 'id']],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => SlipItemSection::class, 'targetAttribute' => ['section_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slip_id' => 'Slip ID',
            'description' => 'Description',
            'value' => 'Value',
            'section_id' => 'Section ID',
        ];
    }

    /**
     * Gets query for [[Section]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(SlipItemSection::class, ['id' => 'section_id']);
    }

    /**
     * Gets query for [[Slip]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSlip()
    {
        return $this->hasOne(Slip::class, ['id' => 'slip_id']);
    }
}
