<?php

namespace backend\modules\i18n\models;

use Yii;

/**
 * This is the model class for table "{{%i18n_source_message}}".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 *
 * @property I18nMessage[] $i18nMessages
 */
class I18nSourceMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $arabicLanguage;
    public $espanolLanguage;
    public static function tableName()
    {
        return '{{%i18n_source_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message','arabicLanguage','espanolLanguage'], 'string'],
            [['message','category'],'required'],
            [['category'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'category' => Yii::t('backend', 'Category'),
            'message' => Yii::t('backend', 'Message'),
            'arabicLanguage' => Yii::t('backend', 'To Arabic'),
            'espanolLanguage' => Yii::t('backend', 'To Espanol'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getI18nMessages()
    {
        return $this->hasMany(I18nMessage::className(), ['id' => 'id']);
    }
}
