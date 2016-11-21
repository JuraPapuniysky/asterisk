<?php
/**
 * Created by PhpStorm.
 * User: wsst17
 * Date: 17.11.16
 * Time: 14:58
 */

namespace common\models;


use PAMI\Message\Action\ActionMessage;

class ConfBridgeListAction extends ActionMessage
{
    public function __construct($conference)
    {
        parent::__construct('ConfbridgeList');
        $this->setKey('Conference',$conference);
    }
}