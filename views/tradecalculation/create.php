<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TradeCalculation */

$this->title = 'Create Trade Calculation';
$this->params['breadcrumbs'][] = ['label' => 'Trade Calculations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-calculation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
