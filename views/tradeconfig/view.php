<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TradeConfig */

$this->title = $model->key;
$this->params['breadcrumbs'][] = ['label' => 'Trade Configs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-config-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->key], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->key], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'key',
            'value',
            'updated_time',
            'created_time',
        ],
    ]) ?>

</div>
