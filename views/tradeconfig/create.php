<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TradeConfig */

$this->title = 'Create Trade Config';
$this->params['breadcrumbs'][] = ['label' => 'Trade Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
