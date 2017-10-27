<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TradeSymbols */

$this->title = 'Update Trade Symbols: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trade Symbols', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="trade-symbols-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
