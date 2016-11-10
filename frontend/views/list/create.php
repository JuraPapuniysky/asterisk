<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ListModel */

$this->title = 'Create List Model';
$this->params['breadcrumbs'][] = ['label' => 'List Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
