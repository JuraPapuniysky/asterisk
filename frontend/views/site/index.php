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

    </div>
</div>
<?php
$script = <<< JS

$('#button').click(function(){
	//alert('Ok');
	$.get('index.php?r=site/call'), function(data){
	var data = $.parseJSON(data);
	alert(data);
	}
	});


JS;
$this->registerJs($script);

?>