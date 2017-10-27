<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%trade_config}}".
 *
 * @property string $key
 * @property string $value
 * @property string $updated_time
 * @property string $created_time
 */
class TradeConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%trade_config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value', 'updated_time', 'created_time'], 'required'],
            [['updated_time', 'created_time'], 'integer'],
            [['key', 'value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => 'Key',
            'value' => 'Value',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }

    //Implement
    public function beforeValidate()
    {
    	if (get_class($this) == "app\models\TradeConfig")
    	{
    		if ($this->isNewRecord)
	        {
	            $this->updated_time = time();
	            $this->created_time = time();
	        }
	        else 
	        {
	            $this->updated_time = time();
	        }
    	}
        
        return parent::beforeValidate();
    }
}
