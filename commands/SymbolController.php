<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\TradeConfig;
use app\models\TradeSymbols;
use app\models\TradeRecord;
use app\models\TradeCalculation;
use Yii;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SymbolController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
	//echo Yii::getAlias("@app");
	//die();
    	$tradeSwitch = TradeConfig::find()->where(["key" => "switch"])->one();

    	if (!$tradeSwitch)
    	{
    		return;
    	} 
    	else 
    	{
			if ($tradeSwitch->value == 0)
			{
				return;
			}
    	}

    	$tradeSymbols = TradeSymbols::find()->where(['active'=> 1])->all();

    	$symbols = [];
    	foreach ($tradeSymbols as $tradeSymbol)
    	{
    		array_push($symbols, $tradeSymbol->symbol);
    	}

    	//Get Info
    	$infoChunks = $this->getInfo($symbols);

    	//Insert & Update Data to Record
    	foreach ($infoChunks as $symbol => $info)
		{
			if ($info != NULL && property_exists ($info, "mkt"))
			{
				$open = 0.0;
				$high = $info->high;
				$low = $info->low;
				$close = $info->last;
				$marketOpen = false;
				
				switch ($info->mkt)
				{
					case 'equity':
						$open = $info->open;
						if (strtolower($info->mktstatus) == "open1" || strtolower($info->mktstatus) == "open2")
		                {
		                        $marketOpen = true;
		                }

						break;
					
					case 'deriv':
						$open = $info->openfix1;
						if (strtolower($info->mktstatus) == "open")
		                {
		                        $marketOpen = true;
		                }
						break;

					default:
						continue;
						break;
				}

				if ($open == 0 || $high == 0 || $low == 0 || $close == 0)
				{
					continue;
				}

				$record = TradeRecord::find()->where(["symbol" => $symbol])->orderby(["date" => SORT_DESC])->one();


				if ($marketOpen)
				{
					if ($record)
					{
						if (date("Y-m-d") == $record->date)
						{
							//UPDATE
							$record->open = $open;
							$record->high = $high;
							$record->low = $low;
							$record->close = $close;

							if (!$record->save())
							{
								$this->sendEmail(Yii::$app->params["subscribeEmail"], "[Database QUERY ERROR]", "Error: " . $symbol . "<br>" . print_r($record->errors, true));
							}
						}
						else
						{
							//INSERT TODAY
							$record = new TradeRecord;
							$record->symbol = $symbol;
							$record->open = $open;
							$record->high = $high;
							$record->low = $low;
							$record->close = $close;
							$record->date = date("Y-m-d");

							if (!$record->save())
							{
								$this->sendEmail(Yii::$app->params["subscribeEmail"], "[Database QUERY ERROR]", "Error: " . $symbol . "<br>" . print_r($record->errors, true));
							}
						}
					}
					else
					{
						//INSERT TODAY
						$record = new TradeRecord;
						$record->symbol = $symbol;
						$record->open = $open;
						$record->high = $high;
						$record->low = $low;
						$record->close = $close;
						$record->date = date("Y-m-d");

						if (!$record->save())
						{
							$this->sendEmail(Yii::$app->params["subscribeEmail"], "[Database QUERY ERROR]", "Error: " . $symbol . "<br>" . print_r($record->errors, true));
						}
					}
				}
				else
				{
					if ($record)
					{
						//UPDATE
						$record->open = $open;
						$record->high = $high;
						$record->low = $low;
						$record->close = $close;
						
						if (!$record->save())
						{
							$this->sendEmail(Yii::$app->params["subscribeEmail"], "[Database QUERY ERROR]", "Error: " . $symbol . "<br>" . print_r($record->errors, true));
						}
					}
				}
			}
		}

		//Calculate Heiken
		$msgCurrentDetect = "";
		foreach ($symbols as $symbol)
		{
			$record = TradeRecord::find()->where(["symbol" => $symbol])->orderby(['date'=>SORT_DESC])->one();

			if (!$record)
			{
				$this->sendEmail(Yii::$app->params["subscribeEmail"], "[Database ERROR]", "Error: " . $symbol . "<br> No Record for calculation");
				continue;
			}


			if ($record)
			{
				$calculations = TradeCalculation::find()->where(['symbol' => $symbol])->limit(2)->orderby(['date' => SORT_DESC])->all();

				if (count($calculations) > 0)
				{
					if ($calculations[0]->date == $record->date)
					{
						//Update
						if (count($calculations) == 2)
						{
							$lastHeiken = $calculations[1];

							$close = ($record->open + $record->high + $record->low + $record->close) / 4;
							$open = ($lastHeiken->open + $lastHeiken->close) / 2;
						    $high = max($open, $close, $record->high);
						    $low = min($open, $close, $record->low);

						    if ($calculations[0]->receive_current == 0)
						    {
						    	$previousState = "";
						    	if ($calculations[0]->open >= $calculations[0]->close)
						    	{
						    		$previousState = "Down";
						    	}
						    	else
						    	{
						    		$previousState = "Up";
						    	}

						    	$currentState = "";
						    	if ($open >= $close)
						    	{
						    		$currentState = "Down";
						    	}
						    	else
						    	{
						    		$currentState = "Up";
						    	}

						    	if ($currentState != $previousState)
						    	{
						    		$msgCurrentDetect .= $symbol." Previos: ".$previousState." Current: ".$currentState."<br/>\n";
						    	}
						    }

						    $calculations[0]->open = $open;
						    $calculations[0]->high = $high;
						    $calculations[0]->low = $low;
						    $calculations[0]->close = $close;

						    if (!$calculations[0]->save())
							{
								$this->sendEmail(Yii::$app->params["subscribeEmail"], "[Database QUERY ERROR]", "Error: " . $symbol . "<br>" . print_r($calculations->errors, true));
							}
						}
					}
					else
					{
						//Insert
						$lastHeiken = $calculations[0];

						$close = ($record->open + $record->high + $record->low + $record->close) / 4;
						$open = ($lastHeiken->open + $lastHeiken->close) / 2;
					    $high = max($open, $close, $record->high);
					    $low = min($open, $close, $record->low);

					    $tradeCalculation = new TradeCalculation;
					    $tradeCalculation->open = $open;
					    $tradeCalculation->high = $high;
					    $tradeCalculation->low = $low;
					    $tradeCalculation->close = $close;
					    $tradeCalculation->symbol = $symbol;
					    $tradeCalculation->date = $record->date;
					    $tradeCalculation->name = "heiken";
					    $tradeCalculation->receive_message = 0;
					    $tradeCalculation->receive_current = 0;

						if (!$tradeCalculation->save())
						{
							$this->sendEmail(Yii::$app->params["subscribeEmail"], "[Database QUERY ERROR]", "Error: " . $symbol . "<br>" . print_r($tradeCalculation->errors, true));
						}
					}
				}
				else
				{
					//Insert Beginner
					$close = ($record->open + $record->high + $record->low + $record->close) / 4;
					$tradeCalculation = new TradeCalculation;
					$tradeCalculation->open = $record->open;
					$tradeCalculation->high = $record->high;
					$tradeCalculation->low = $record->low;
					$tradeCalculation->close = $close;
					$tradeCalculation->symbol = $symbol;
					$tradeCalculation->date = $record->date;
					$tradeCalculation->name = "heiken";
					$tradeCalculation->receive_message = 0;
					$tradeCalculation->receive_current = 0;

					if (!$tradeCalculation->save())
					{
						$this->sendEmail(Yii::$app->params["subscribeEmail"], "[Database QUERY ERROR]", "Error: " . $symbol . "<br>" . print_r($tradeCalculation->errors, true));
					}
				}

			}
		}

		if ($msgCurrentDetect != "")
		{
			$this->sendEmail(Yii::$app->params["subscribeEmail"], "Detect Current Change Trend: ".gmdate("d-m-Y H:i:s", time() + (3600 * 7)), $msgCurrentDetect);
		}

		//Send Noti
		$allSendMessage = "";

		foreach ($symbols as $symbol)
		{
			$calculations = TradeCalculation::find()->where(['symbol' => $symbol])->limit(2)->orderby(['date' => SORT_DESC])->all();

			if (count($calculations) == 2)
			{
				$presentData = $calculations[0];
				$pastData = $calculations[1];

				//Check Noti
				if ($presentData->receive_message == 0)
				{
					$diffPastData = $pastData->open - $pastData->close;

					if ($diffPastData != 0)
					{
						$diffPastData = $diffPastData / abs($diffPastData);	
					}
					

					$diffPresentData = $presentData->open - $presentData->close;

					if ($diffPresentData != 0)
					{
						$diffPresentData = $diffPresentData / abs($diffPresentData);	
					}

					if ($diffPastData != $diffPresentData)
					{
						$allSendMessage .= $symbol." is changed trend.<br/>\n";
					}
				}
			}
		}

		if ($allSendMessage != "")
		{
			$this->sendEmail(Yii::$app->params["subscribeEmail"], "Detect Change Trend: ".gmdate("d-m-Y H:i:s", time() + (3600 * 7)), $allSendMessage);
		}
    }

    public function getInfo($symbols)
    {
		$infoChunk = array();

		file_put_contents(Yii::getAlias('@app').'/cookietnsi.txt', '');
		$cookie_file_path = realpath(Yii::getAlias('@app')."/cookietnsi.txt");
		
		foreach ($symbols as $symbol)
		{
			$infoChunk[$symbol] = NULL;
		}

		$ch = curl_init();

		$url="http://wwwa1.settrade.com/tns2/pre_logged_in/page/loginForm.jsp?frame=no&txtLanguage=th";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);   
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_COOKIESESSION, true);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path); 
		curl_exec($ch);

		//Login **************************************************************************
		$url = "http://wwwa1.settrade.com/mylib.jsp?txtBrokerId=016";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_exec($ch);

		$url = "https://wen20.settrade.com/LoginRepOnRole.jsp";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		$postinfo = "txtSecureKey=NONE&txtBrokerId=016&txtLogin=hull2525&txtLoginPage=http://wen20.settrade.com/tns2/&txtPassword=]6,rbou&txtLanguage=th&open=&submit.x=0&submit.y=0";
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
		curl_exec($ch);

		$url = "https://wd03.settrade.com/LoginBySystem.jsp";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		$postinfo = "txtLogin=hull2525&txtPassword=]6,rbou&txtBrokerId=016&txtRole=&txtDefaultPage=&txtSecureKey=NONE&txtLanguage=th&txtUserClass=&txtLoginPage=http://wen20.settrade.com/tns2/&txtService=&txtSystem=&txtRealRole=INTERNET&txtLoginType=&txtSessionId=&txtJSSupport=&txtMaintenanceLogin=&txtSTTLogin=hull2525&txtDefaultCentralServer=wwwa1.settrade.com&txtAccountNo=&txtUserName=hull2525&txtLoginFailed=&txtUKey=hull2525_016";
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
		curl_exec($ch);

		$url = "https://wd03.settrade.com/Derivatives/C13_MarketSummary.jsp";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POST, 1);
		$postinfo = "txtUserName=hull2525&txtHostName=wd03.settrade.com&txtLanguage=th&txtLanguage=th&defaultPage=/Derivatives/C13_MarketSummary.jsp&txtDefaultPage=/Derivatives/C13_MarketSummary.jsp";
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
		curl_exec($ch);

		$url = "https://wd03.settrade.com/Derivatives/C13_MarketSummaryMM.jsp";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_exec($ch);

		$url = "https://wd03.settrade.com/realtime/fastorder/fastorder.jsp?platform=mm";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POST, 0);
		$html = curl_exec($ch);
		//End Login ***********************************************************************

		foreach ($symbols as $symbol)
		{
			//Get Key
			$url = "https://wd03.settrade.com/webrealtime/script/genKey.jsp?type=fastquote";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($ch, CURLOPT_POST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Referer: https://wd03.settrade.com/realtime/fastorder/fastorder.jsp?platform=mm'));
			$key = str_replace("\"", "", str_replace("var aj = \"", "", trim(trim(curl_exec($ch)), ";")));

			$url = "https://wd03.settrade.com/webrealtime/data/fastquote.jsp";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POST, 1);
			$postinfo = "symbol=".$symbol."&key=".$key;
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
			$info = json_decode(curl_exec($ch));

			$infoChunk[$symbol] = $info;
		}

		//Logout **************************************************************************
		$url = "https://wd03.settrade.com/Derivatives/C02_LogoutRep.jsp?txtHome=login.jsp";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_exec($ch);
		//End Logout **********************************************************************

		curl_close($ch);

		return $infoChunk;
    }

    public function sendEmail($to, $subject, $message)
    {
    	$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, 'api:'.Yii::$app->params["mailgunAPI"]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$plain = strip_tags(nl2br($message));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v3/'.Yii::$app->params["mailgunDomain"].'/messages');
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('from' => 'support@'.Yii::$app->params["mailgunDomain"],
		    'to' => $to,
		    'subject' => $subject,
		    'html' => $message,
		    'text' => $plain));

		$j = json_decode(curl_exec($ch));

		$info = curl_getinfo($ch);

		if($info['http_code'] != 200)
		{
			echo("Can't Send via support@".DOMAIN);
		}
		    

		curl_close($ch);

		return $j;
    }

}
