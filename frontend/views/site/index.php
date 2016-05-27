<?php

/* @var $this yii\web\View */


$this->title = 'My Yii Application';


?>
<div class="site-index">


    <div class="body-content">

        Peers
        <?php foreach ($peers as $peer){ ?>
    <pre>
        <?php  print_r($peer->getKeys()); ?>
    </pre>
        <?php } ?>

        Commands

    <pre>
        <?php  print_r($list_command); ?>
    </pre>

        command action
        <pre>
        <?php  print_r($command_action); ?>
    </pre>
        Originate
        <pre>
        <?php  print_r($originate); ?>
    </pre>


    </div>
</div>
