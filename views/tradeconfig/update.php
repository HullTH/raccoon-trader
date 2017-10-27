<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TradeConfig */

$this->title = 'Update Trade Config: ' . ' ' . $model->key;
$this->params['breadcrumbs'][] = ['label' => 'Trade Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->key, 'url' => ['view', 'id' => $model->key]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="trade-config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
