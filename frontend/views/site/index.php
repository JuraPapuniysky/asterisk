<?php

/* @var $this yii\web\View */

/* @var $conferences */

use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'My Yii Application';

?>

<div class="site-index">


    <div class="body-content">
    <?php Pjax::begin();
     echo Html::a(
         'Обновить',
         ['/site/index/'],
         ['class' => 'btn btn-lg btn-primary', 'id' => 'refreshButton']
     ); ?>

        <?php foreach ($conferences as $conference) { ?>
    <table class="table">
        <thead>
        <tr>
            <th>Номер конференции</th>
            <th>Номер пользователя</th>
            <th>Канал</th>
            <th>Приглушен</th>
            <th>Видео</th>
            <th>Управление</th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($conference as $user){ ?>
            <tr>
                <td><?= $user->conference ?></td>
                <td><?= $user->callerId ?></td>
                <td><?= $user->channel ?></td>
                <td><?php if($user->mutted == false){
                        echo 'No';
                    }else{
                        echo 'Yes';
                    } ?></td>
                <td><?php if($user->video == false){
                        echo 'No';
                    }else{
                        echo 'Yes';
                    } ?></td>
                <td><?php if($user->mutted == false)
                    {
                        echo Html::a(
                            'Выключить микрофон',
                            ['/site/mute/', 'channel' => $user->channel ],
                            ['class' => 'btn btn-lg btn-danger', 'id' => 'muted_user']
                        );
                    }else{
                        echo Html::a(
                            'Включить микрофон',
                            ['/site/unmute/', 'channel' => $user->channel ],
                            ['class' => 'btn btn-lg btn-success', 'id' => 'unmuted_user']
                        );
                    }?></td>
            </tr>
            <?php } ?>
        </tbody>

    <?php } ?>
    </table>
    <?php Pjax::end(); ?>
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