<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TradeCalculation */

$this->title = 'Update Trade Calculation: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Trade Calculations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="trade-calculation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
