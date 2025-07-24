<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_document_templates".
 *
 * @property int $id
 * @property string|null $document_type
 * @property string|null $language
 * @property string|null $version
 * @property int|null $is_active
 * @property string|null $content
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class DocumentTemplate extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_document_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_type', 'language', 'content'], 'required'], // Make these required fields
            [['content'], 'string'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'], // Handled by behaviors or default DB values
            [['document_type', 'language', 'version'], 'string', 'max' => 255],
            // Ensure document_type and language combination is unique for active templates
            // This rule helps prevent multiple active templates for the same type/language.
            ['document_type', 'unique', 'targetAttribute' => ['document_type', 'language', 'is_active'], 'message' => 'An active template for this document type and language already exists.', 'when' => function($model) {
                return $model->is_active == 1;
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_type' => 'Document Type',
            'language' => 'Language',
            'version' => 'Version',
            'is_active' => 'Is Active',
            'content' => 'Template Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Set created_at and updated_at automatically.
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'value' => new \yii\db\Expression('NOW()'),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    /**
     * Helper to get available document types (for dropdowns).
     * You can define a more robust list from a config or database if needed.
     */
    public static function getDocumentTypes()
    {
        return [
            'Employment Certificate' => 'Employment Certificate',
            'Salary Certificate' => 'Salary Certificate',
            'Experience Letter' => 'Experience Letter',
            'Visa Letter' => 'Visa Letter',
            'Reference Letter' => 'Reference Letter',
        ];
    }

    /**
     * Helper to get available languages (for dropdowns).
     */
    public static function getLanguages()
    {
        return [
            'English' => 'English',
            'Arabic' => 'Arabic',
            // Add more as needed
        ];
    }
}