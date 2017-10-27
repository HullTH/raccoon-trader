<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%trade_calculation}}".
 *
 * @property string $id
 * @property string $symbol
 * @property string $name
 * @property string $date
 * @property double $open
 * @property double $high
 * @property double $low
 * @property double $close
 * @property integer $receive_message
 * @property integer $receive_current
 * @property string $updated_time
 * @property string $created_time
 */
class TradeCalculation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%trade_calculation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['symbol', 'name', 'date', 'open', 'high', 'low', 'close', 'receive_message', 'receive_current', 'updated_time', 'created_time'], 'required'],
            [['date'], 'safe'],
            [['open', 'high', 'low', 'close'], 'number'],
            [['receive_message', 'receive_current', 'updated_time', 'created_time'], 'integer'],
            [['symbol', 'name'], 'string', 'max' => 255]
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
            'name' => 'Name',
            'date' => 'Date',
            'open' => 'Open',
            'high' => 'High',
            'low' => 'Low',
            'close' => 'Close',
            'receive_message' => 'Receive Message',
            'receive_current' => 'Receive Current',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }

    //Implement
    public function beforeValidate()
    {
    	if (get_class($this) == "app\models\TradeCalculation")
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
