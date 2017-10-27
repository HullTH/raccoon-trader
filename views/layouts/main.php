<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Raccoon',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Management', 
                'items' => [
                   ['label' => 'Calculations', 'url' => ['/tradecalculation/index']],
                   ['label' => 'Records', 'url' => ['/traderecord/index']],
                   ['label' => 'Configs', 'url' => ['/tradeconfig/index']],
                   ['label' => 'Symbols', 'url' => ['/tradesymbols/index']],
            ]],
            Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/site/login']] :
                [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">

    	<p class="pull-right" id="panel_switch" style="display:none;">
    		<span class="label label-default">Updated Switch</span>
    		<input id="switchUpdate" type="checkbox" data-off-color="default" data-size="mini" data-on-color="default" name="my-checkbox" checked>
    	</p>
        
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Raccoon <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
<script type="text/javascript">
	$(document).ready(function() {
		$.get("<?= Yii::$app->urlManager->createUrl(["site/switch"])?>", function(data, status){
		 	var obj = jQuery.parseJSON(data);
		 	if (obj.value == true) {
		 		$('#switchUpdate').bootstrapSwitch('state', true, false);
		 	} else {
		 		$('#switchUpdate').bootstrapSwitch('state', false, false);
		 	}

		 	$("#panel_switch").show();

		 	$('#switchUpdate').on('switchChange.bootstrapSwitch', function(event, state) {
				$.post( "<?= Yii::$app->urlManager->createUrl(["site/switch"]) ?>", { value: "1"})
					.done(function( data ) {
				  });
			});
		});
	});
</script>
</body>
</html>
<?php $this->endPage() ?>
