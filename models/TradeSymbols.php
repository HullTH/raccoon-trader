<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%trade_symbols}}".
 *
 * @property string $id
 * @property string $symbol
 * @property integer $active
 * @property string $updated_time
 * @property string $created_time
 */
class TradeSymbols extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%trade_symbols}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['symbol', 'active', 'updated_time', 'created_time'], 'required'],
            [['active', 'updated_time', 'created_time'], 'integer'],
            [['symbol'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'symbol' => 'Symbol',
            'active' => 'Active',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }

    //Implement
    public function beforeValidate()
    {
    	if (get_class($this) == "app\models\TradeSymbols")
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
