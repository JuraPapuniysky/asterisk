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

        <?php echo Html::a(
         '',
         ['/site/index/'],
         ['class' => 'glyphicon glyphicon-refresh', 'id' => 'refreshButton']
     );
        if($conferences == null)
        {?>
             <h3><span class="label label-info">Нет активных конференций</span></h3>

        <?php }?>

        <?php foreach  ($conferences as $conference) { $i = 0; $conf = $conference[$i];?>

        <h3><span class="label label-info">Список учасников конференции <?= $conf->conference?></span></h3>
            <?php
            echo Html::a(
                "Выключить все микрофоны $conf->conference",
                ['/site/mutte-unmutte-all/', 'conference' => $conf->conference, 'action' => 'yes'],
                ['class' => 'btn btn-lg btn-danger', 'id' => 'muted_user']
            );

            echo Html::a(
                "Включить все микрофоны $conf->conference",
                ['/site/mutte-unmutte-all/', 'conference' => $conf->conference, 'action' => 'no' ],
                ['class' => 'btn btn-lg btn-success', 'id' => 'muted_user']
            );

            ?>

    <div class="row">

    <table class="table table table-condensed table-hover table-bordered">
        <thead>
        <tr>
            <th></th>
            <th>№ конф</th>
            <th>Имя</th>
            <th>Номер</th>
            <th>Управление</th>
        </tr>
        </thead>
        <tbody>
            <?php $i++; foreach ($conference as $user){ ?>
            <tr>
                <td><?= Html::a(
                        "<span class=\"glyphicon glyphicon-remove\">",
                        ['/site/kick/', 'conference' => $user->conference, 'channel' => $user->channel ],
                        ['class' => '', 'data-confirm' => "Вы уверены, что хотите удалить пользователя $user->name из конференции?"]
                    ) ?></td>
                <td><?= $user->conference ?></td>
                <td><?= $user->name ?></td>
                <td><?= $user->callerId ?></td>
                <td><?php if($user->mutted == 'no')
                    {
                        echo Html::a(
                            '<small>Выключить микрофон</small>',
                            ['/site/mutte/', 'conference' => $user->conference, 'channel' => $user->channel ],
                            ['class' => 'btn btn-lg btn-danger btn btn-mini', 'id' => 'muted_user']
                        );
                    }else if($user->mutted == 'yes'){
                        echo Html::a(
                            '<small>Включить микрофон</small>',
                            ['/site/unmutte/', 'conference' => $user->conference, 'channel' => $user->channel ],
                            ['class' => 'btn btn-lg btn-success btn btn-mini', 'id' => 'unmuted_user']
                        );
                    }?></td>
                <td><?= Html::a(
                        '<small>Отобразить всем</small>',
                        ['/site/set-single-video/', 'conference' => $user->conference, 'channel' => $user->channel],
                        ['class' => 'btn btn-lg btn-success', 'id' => 'muted_user']) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

    <?php Pjax::end(); ?>
    </div>


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

