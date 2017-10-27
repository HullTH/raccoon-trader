<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TradeCalculation */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord)
{
	$model->name = 'heiken';
	$model->receive_message = 0;
	$model->receive_current = 0;
}

?>

<div class="trade-calculation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'symbol')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'open')->textInput() ?>

    <?= $form->field($model, 'high')->textInput() ?>

    <?= $form->field($model, 'low')->textInput() ?>

    <?= $form->field($model, 'close')->textInput() ?>

    <?= $form->field($model, 'receive_message')->textInput() ?>

    <?= $form->field($model, 'receive_current')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
