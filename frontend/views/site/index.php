<?php

/* @var $this yii\web\View */

/* @var $conferences */

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Актиные конференции';

?>

<div class="site-index">


    <div class="body-content">
    <?php Pjax::begin();?>

    <?php echo \common\widgets\ConfUsers::widget(['conferences' => $conferences, 'size' => 10]); ?>

    <?php Pjax::end(); ?>
    </div>
  </div>



</div>


