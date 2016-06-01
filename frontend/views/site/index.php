<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'My Yii Application';

?>

<div class="site-index">


    <div class="body-content">
        <?php
        Pjax::begin();
        echo Html::a(
        'Обновить',
        ['/site/index/'],
        ['class' => 'btn btn-lg btn-primary', 'id' => 'refreshButton']
        );?>
        Учасники конференции <?= Yii::$app->pamiconn->generalConference ?>
        <table class="table">
            <thead>
            <tr>
                <th>Номер конференции</th>
                <th>Номер пользователя</th>
                <th>Канал</th>
                <th>Приглушен</th>
                <th>Управление</th>
            </tr>
            </thead>
            <tbody>
           <?php
           foreach ($module as $mod){

               $keys = $mod->getKeys();
               if($keys['event'] == 'MeetmeList'){ ?>
                   <tr>
                       <td><?= $keys['conference'] ?></td>
                       <td><?= $keys['calleridnum'] ?></td>
                       <td><?= $keys['channel'] ?></td>
                       <td><?= $keys['muted'] ?></td>
                       <td><?php if($keys['muted'] == 'No')
                            {
                                echo Html::a(
                                    'Выключить',
                                    ['/site/mute/', 'usernumber' => $keys['usernumber']],
                                    ['class' => 'btn btn-lg btn-danger', 'id' => 'muted_user']
                                );
                            }else{
                               echo Html::a(
                                   'Включить',
                                   ['/site/unmute/', 'usernumber' => $keys['usernumber']],
                                   ['class' => 'btn btn-lg btn-success', 'id' => 'unmuted_user']
                               );
                           }?></td>
                   </tr>
               <?php }}
                ?>
            </tbody>
        </table>
        <pre>
            <?php print_r($message); ?>
        </pre>
            <?php Pjax::end();
            ?>


    </div>
</div>
<?php
$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, 10000);
});
JS;
$this->registerJs($script);
?>