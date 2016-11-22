<?php

/* @var $this yii\web\View */

/* @var $conferences */

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Актиные конференции';

?>

<div class="site-index">



    <div class="body-content">



    <?= \common\widgets\ListUsers::widget(['conferences' => $conferences]) ?>





    </div>
  </div>



</div>


