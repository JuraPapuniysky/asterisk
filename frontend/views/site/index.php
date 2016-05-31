<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'My Yii Application';


?>
<div class="site-index">


    <div class="body-content">

    <?php \yii\widgets\Pjax::begin();?>
    <?= Html::a('Звонить',['site/call'],['class' =>'btn btn-lg btn-primary']) ?>
    <?php \yii\widgets\Pjax::end()?>



    <?php \yii\widgets\Pjax::begin();?>
    <?= Html::a('Подключить к конференции',['site/redirect'],['class' =>'btn btn-lg btn-primary']) ?>
    <?php \yii\widgets\Pjax::end()?>

    </div>

       
    <?php foreach ($module as $mod){  ?>
    <pre>
        <?php print_r($mod->getKeys()); ?>
    </pre>
    <?php }?>

</div>
