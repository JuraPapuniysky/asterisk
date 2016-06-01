<?php



use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'test';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-test">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
           <pre>
               <?php print_r($message) ?>
           </pre>
        </div>
    </div>
</div>
