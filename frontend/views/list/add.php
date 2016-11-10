<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\ListClient */

$this->title = 'Добавить номер';
$this->params['breadcrumbs'][] = ['label' => 'List Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$model;
$clients;
$lists;
?>

<div class="list-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="list-client-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'client_id')->dropDownList(ArrayHelper::map($clients, 'id', 'name'))?>

        <?php if ($model->list_id != null) {
             echo $form->field($model, 'list_id')->textInput(['maxlength' => true]);
        }else{
             echo $form->field($model, 'list_id')->dropDownList(ArrayHelper::map($lists, 'id', 'name'));
        }
        ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
