<?php



use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'test';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-test">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php
    echo Html::a(
    "Звонить",
    ['/site/call/', 'conference' => 501, 'callerid' => '111'],
    ['class' => 'btn btn-lg btn-danger', 'id' => 'muted_user']
    );

    ?>

</div>
