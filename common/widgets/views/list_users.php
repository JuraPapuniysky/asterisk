<?php
/* @var  $user \common\models\ConferenceUsers */

use yii\helpers\Html;
use rmrevin\yii\fontawesome\cdn\AssetBundle;
use yii\widgets\Pjax;

$headpiece = \common\models\Clients::getHeadpiece();
AssetBundle::register($this);


?>
<?php
$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, 3000);
});

//$(document).ready(function() {
//    setInterval(function(){ location.reload(); }, 20000);
//});
JS;
$this->registerJs($script);
?>
<div class="row">

    <div class="col-md-2">

    </div>
</div>

<div class="row">
    <?php

    Pjax::begin(); ?>
    <div class="col-md-7">
        <?php echo Html::a(
            '',
            ['/site/index/'],
            ['class' => 'glyphicon glyphicon-refresh', 'id' => 'refreshButton',]

        ); ?>
        <table class="table table table-condensed table-hover table-bordered">

            <thead>
            <tr>
                <th><?php echo Html::a(
                        "",
                        ['/site/kick-all/',],
                        ['class' => 'btn btn-danger btn-sm glyphicon glyphicon-remove', 'id' => 'kick_users']
                    );
                    ?></th>
                <th><?php echo Html::a(
                        "",
                        ['/site/call-list/',],
                        ['class' => 'btn btn-success btn-sm fa fa-phone', 'id' => 'call_users']
                    );
                    ?></th>
                <th></th>
                <th>№ конф</th>
                <th>Имя участника конференции</th>
                <th>Номер</th>
                <th>
                    <div class="row">
                        <div class="col-md-1">
                            <?php
                            echo Html::a(
                                    "",
                                    ['/site/mutte-unmutte-all/', 'action' => 'yes'],
                                    ['class' => 'btn btn-xs btn-danger fa fa-microphone-slash fa-5x', 'id' => 'muted_user']
                                ) . '<br />';
                            ?>
                        </div>
                        <div class="col-md-1"><?php
                            echo Html::a(
                                "",
                                ['/site/mutte-unmutte-all/', 'action' => 'no'],
                                ['class' => 'btn btn-xs btn-success fa fa-microphone fa-5x', 'id' => 'muted_user']
                            );

                            ?></div>
                    </div>
                    _______
                </th>
                <th style="color: #ffffff">---------------</th>
                <th style="color: #ffffff">---------------</th>
            </tr>
            </thead>
            <tbody>
            <?php


            $count = 1;
            foreach ($conferences as $user) { ?>
                <?php if ($user->isActive) { ?>
                    <tr class="text-success">
                <?php } else { ?>
                    <tr>
                <?php } ?>
                <td><?= Html::a(
                        "<span class=\"glyphicon glyphicon-remove\">",
                        ['/site/kick/', 'conference' => $user->conference, 'channel' => $user->channel],
                        ['class' => '',]
                    ) ?></td>
                <td><?php
                    if (!$user->isActive) {
                        echo Html::a(
                            "",
                            ['/site/call/', 'conference' => Yii::$app->pamiconn->generalConference, 'callerid' => $user->callerId],
                            ['class' => 'btn btn-sm btn-default fa fa-phone', 'id' => 'call-button', 'data-pjax' => 0]);
                    } else {
                        echo Html::a(
                            "",
                            ['/site/index/',],
                            ['class' => 'btn btn-sm btn-success fa fa-phone', 'id' => 'call-button', 'data-pjax' => 0]);
                    }


                    ?></td>
                <td><?= $count ?></td>
                <td><?= $user->conference ?></td>
                <td><?= $user->name ?></td>
                <td><?= $user->callerId ?></td>
                <td><?php if ($user->mutte == 'no' and $user->isActive) {
                        echo Html::a(
                            '',
                            ['/site/mutte/', 'userid' => $user->id,],
                            ['class' => 'btn btn-sm btn-success fa fa-microphone fa-2x', 'id' => 'muted_user',]
                        );
                    } else if ($user->mutte == 'yes' and $user->isActive) {
                        echo Html::a(
                            '',
                            ['/site/unmutte/', 'userid' => $user->id],
                            ['class' => 'btn btn-sm btn-danger fa fa-microphone-slash fa-2x', 'id' => 'unmuted_user',]

                        );
                    } ?></td>
                <td><?php if ($user->isActive and $user->video == 'no') {
                        echo Html::a(
                            '<span class="glyphicon glyphicon-facetime-video"></span>',
                            ['/site/set-single-video/', 'conference' => $user->conference, 'channel' => $user->channel],
                            ['class' => 'btn btn-default btn-sm btn-block', 'id' => 'muted_user']);
                    } elseif ($user->isActive and $user->video == 'yes') {
                        echo Html::a(
                            '<span class="glyphicon glyphicon-facetime-video"></span>',
                            ['/site/set-single-video/', 'conference' => $user->conference, 'channel' => $user->channel],
                            ['class' => 'btn btn-success btn-sm btn-block', 'id' => 'muted_user', 'autofocus']);

                    }
                    ?>
                </td>
                <td><?php if ($user->isActive) {
                        echo Html::a(
                            '<span class="glyphicon glyphicon-picture"></span>',
                            ['/site/set-single-video/', 'conference' => $headpiece->conference, 'channel' => $headpiece->channel],
                            ['class' => 'btn btn-default btn-sm btn-block', 'id' => 'muted_user']);
                    } ?>
                </td>
                </tr>

                <?php $count++;
            } ?>


            </tbody>
        </table>

    </div>
    <?php Pjax::end(); ?>
</div>

