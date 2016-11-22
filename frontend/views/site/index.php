<?php

/* @var $this yii\web\View */

/* @var $conferences */

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Актиные конференции';

?>

<div class="site-index">



    <div class="body-content">

    <?php //Pjax::begin();?>

    <?= \common\widgets\ListUsers::widget(['conferences' => $conferences]) ?>

    <?php //Pjax::end(); ?>



    </div>
  </div>



</div>


