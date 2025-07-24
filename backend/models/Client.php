<?php

namespace backend\models;

use Yii;
use backend\models\clientFixed\ClientFixed;
use backend\models\FileUpload;
use app\models\ClientMultiSelect;
use backend\models\ClientOrganisation;
use common\models\User;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property integer $organisation_id
 * @property integer $user_id
 * @property string $email
 * @property string $client_name
 * @property string $country
 * @property string $phone
 * @property string $address
 * @property string $text_1570532600638
 * @property string $text_1578126561394
 * @property string $additional_attachments
 *
 * @property Applicant[] $applicants
 * @property Organisation $organisation
 * @property User $user
 * @property InviteApplicant[] $inviteApplicants
 * @property Organisation[] $organisations



 */
class Client extends ClientFixed
{

    // Temporary attribute for holding selected organisation IDs
    public $selectedOrganisations = [];
    
    public static function returnAttachmentAttr()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organisation_id', 'user_id'], 'integer'],
            // [['email', 'client_name', 'country', 'phone', 'address'], 'required'],
            [['client_name'], 'required'],
            [['address'], 'string'],
            [['email', 'country', 'phone', 'text_1570532600638', 'text_1578126561394','additional_attachments'], 'string', 'max' => 255],
            ['email','email'],
            [['selectedOrganisations'], 'required', 'message' => 'Please select at least one northman entity.'], // Add this line
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'organisation_id' => '',
            'user_id' => '',
            'email' => 'Email',
            'client_name' => 'Client Name',
            'country' => 'Country',
            'phone' => 'Phone',
            'address' => 'Address',
            'text_1570532600638' => 'Company Registration Number',
            'text_1578126561394' => 'Company VAT Registration Number',
            'additional_attachments' => 'Additional Attachments',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplicants()
    {
        return $this->hasMany(Applicant::className(), ['client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisation()
    {
        return $this->hasOne(Organisation::className(), ['id' => 'organisation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInviteApplicants()
    {
        return $this->hasMany(InviteApplicant::className(), ['client_id' => 'id']);
    }

    public function getOrganisations()
    {
        return $this->hasMany(ClientOrganisation::className(), ['client_id' => 'id']);
    }

    public static function getClientUser($clientId)
    {
        // Fetch all users with the given client_id
        $users = User::find()->where(['client_id' => $clientId])->all();

        // Get the AuthManager component for RBAC
        $auth = Yii::$app->authManager;

        // Iterate through the users and check if they have the 'Client' role
        foreach ($users as $user) {
            if ($auth->checkAccess($user->id, 'Client')) {
                return $user; // Return the user with the 'Client' role
            }
        }

        // Return null if no user with 'Client' role was found
        return null;
    }
}
