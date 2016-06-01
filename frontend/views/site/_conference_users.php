<?php
foreach ($module as $mod){

    $keys = $mod->getKeys();
    if($keys['event'] == 'MeetmeList'){ ?>
        <tr>
            <td><?= $keys['conference'] ?></td>
            <td><?= $keys['calleridnum'] ?></td>
            <td><?= $keys['channel'] ?></td>
            <td><?= $keys['muted'] ?></td>
        </tr>
    <?php }} ?>