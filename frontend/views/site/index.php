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
            <th>Имя пользователя</th>
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
                <td><?= $user->name ?></td>
                <td><?= $user->callerId ?></td>
                <td><?= $user->channel ?></td>
                <td><?= $user->mutted ?></td>
                <td><?= $user->video ?></td>
                <td><?php if($user->mutted == 'no')
                    {
                        echo Html::a(
                            'Выключить микрофон',
                            ['/site/mute/', 'channel' => $user->channel ],
                            ['class' => 'btn btn-lg btn-success', 'id' => 'muted_user']
                        );
                    }else if($user->mutted == 'yes'){
                        echo Html::a(
                            'Включить микрофон',
                            ['/site/unmute/', 'channel' => $user->channel ],
                            ['class' => 'btn btn-lg btn-danger', 'id' => 'unmuted_user']
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