<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%trade_record}}".
 *
 * @property string $id
 * @property string $symbol
 * @property string $date
 * @property double $open
 * @property double $high
 * @property double $low
 * @property double $close
 * @property string $updated_time
 * @property string $created_time
 */
class TradeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%trade_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['symbol', 'date', 'open', 'high', 'low', 'close', 'updated_time', 'created_time'], 'required'],
            [['date'], 'safe'],
            [['open', 'high', 'low', 'close'], 'number'],
            [['updated_time', 'created_time'], 'integer'],
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
            'date' => 'Date',
            'open' => 'Open',
            'high' => 'High',
            'low' => 'Low',
            'close' => 'Close',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }

    //Implement
    public function beforeValidate()
    {
    	if (get_class($this) == "app\models\TradeRecord")
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
