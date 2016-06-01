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
            </tr>
            </thead>
            <tbody>
           <?php
            echo $this->render('_conference_users', [
                'module' => $module,
            ]);
                ?>
            </tbody>
        </table>
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