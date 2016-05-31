<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'My Yii Application';


?>
<div class="site-index">


    <div class="body-content">
        Учасники конференции <?= Yii::$app->pamiconn->generalConference ?>
               <table class="table">
            <thead>
            <tr>
                <th>Номер конференции</th>
                <th>Номер пользователя</th>
                <th>Канал</th>
            </tr>
            </thead>
            <tbody>
            <?php
            Pjax::begin();
            foreach ($module as $mod){  ?>
            <?php
            $keys = $mod->getKeys();
            if($keys['event'] == 'MeetmeList'){ ?>
                <tr>
                    <td><?= $keys['conference'] ?></td>
                    <td><?= $keys['calleridnum'] ?></td>
                    <td><?= $keys['channel'] ?></td>
                </tr>
            <?php }}
            Pjax::end();
            ?>
    
            <?= Html::a('Обновить', ['/site/index'], ['class' => 'btn btn-lg btn-primary', 'id' => 'refreshButton']) ?>

            </tbody>
        </table>

    </div>
</div>
<?php
$script = <<< JS
$(document).ready(function() {
    setInterval(function(){
        $('#refreshButton').click();
    }, 1000);
});
JS;
$this->registerJs($script);
?>