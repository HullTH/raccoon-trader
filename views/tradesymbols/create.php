<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TradeSymbols */

$this->title = 'Create Trade Symbols';
$this->params['breadcrumbs'][] = ['label' => 'Trade Symbols', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-symbols-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
