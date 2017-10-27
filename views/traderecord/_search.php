<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\controllers\TraderecordSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trade-record-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'symbol') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'open') ?>

    <?= $form->field($model, 'high') ?>

    <?php // echo $form->field($model, 'low') ?>

    <?php // echo $form->field($model, 'close') ?>

    <?php // echo $form->field($model, 'updated_time') ?>

    <?php // echo $form->field($model, 'created_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
