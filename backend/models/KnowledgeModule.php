<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_knowledge_module".
 *
 * @property int $id
 * @property int $case_type_id
 * @property string|null $query
 * @property string|null $notes
 *
 * @property CaseType $caseType
 */
class KnowledgeModule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_knowledge_module';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['case_type_id'], 'required'],
            [['case_type_id'], 'integer'],
            [['notes'], 'string'],
            [['query'], 'string', 'max' => 255],
            [['case_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CaseType::class, 'targetAttribute' => ['case_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'case_type_id' => 'Case Type ID',
            'query' => 'Query',
            'notes' => 'Notes',
        ];
    }

    /**
     * Gets query for [[CaseType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCaseType()
    {
        return $this->hasOne(CaseType::class, ['id' => 'case_type_id']);
    }
}
