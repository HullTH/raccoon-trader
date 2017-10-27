<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\controllers\TradecalculationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trade Calculations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-calculation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Trade Calculation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'symbol',
            'name',
            'date',
            'open',
            // 'high',
            // 'low',
            // 'close',
            // 'receive_message',
            // 'receive_current',
            // 'updated_time',
            // 'created_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
