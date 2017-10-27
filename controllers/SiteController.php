<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\TradeConfig;
use app\models\TradeSymbols;
use app\models\TradeCalculation;

class SiteController extends Controller
{
	public function beforeAction($action)
	{            
	    if ($action->id == 'switch' || $action->id == 'symbol') {
	        $this->enableCsrfValidation = false;
	    }

	    return parent::beforeAction($action);
	}

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
    	$symbols = TradeSymbols::find()->where(["active" => "1"])->all();
    	
    	$currents = [];
    	$lasts = [];
    	foreach ($symbols as $symbol)
    	{
    		$calculations = TradeCalculation::find()->where(['symbol' => $symbol->symbol])->orderby(['date' => SORT_DESC])->limit(2)->all();
    		
    		if (isset($calculations[0]))
    		{
    			$currents[$symbol->symbol] = $calculations[0];
    		}

    		if (isset($calculations[0]))
    		{
    			$lasts[$symbol->symbol] = $calculations[1];
    		}
    	}

        return $this->render('index', [
        					 'symbols' => $symbols,
        					 'currents' => $currents,
        					 'lasts' => $lasts,
        				]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSwitch()
    {
    	if (isset($_POST["value"]))
    	{
    		$switch = TradeConfig::find()->where(['key' => 'switch'])->one();
    		$switch->value = $switch->value == 1 ? "0" : "1";

    		$switch->save();

    		return;
    	}

    	$switch = TradeConfig::find()->select('value')->where(['key' => 'switch'])->one();
    	$switchValue["value"] = $switch->value == 1;
    	echo json_encode($switchValue);
    }

    public function actionSymbol()
    {
    	if (isset($_POST["id"]) && isset($_POST["update_state"]))
    	{
    		$calculation = TradeCalculation::find()->where(["id" => $_POST["id"]])->one();

    		if ($calculation)
    		{
    			switch($_POST["update_state"])
    			{
    				case "current":
    					$calculation->receive_current = $calculation->receive_current == 1 ? 0 : 1;
    				break;
    				case "last":
    					$calculation->receive_message = $calculation->receive_message == 1 ? 0 : 1;
    				break;
    			}

    			$calculation->save();
    		}
    	}
    }
}
