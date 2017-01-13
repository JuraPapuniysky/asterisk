<?php
/**
 * Created by PhpStorm.
 * User: wsst17
 * Date: 11.01.17
 * Time: 10:42
 */

namespace common\models;


use PAMI\Message\Action\OriginateAction;


class Originate extends OriginateAction
{
    public function __construct(Array $callerIds)
    {
        foreach ($callerIds as $callerId){
            $this->setKey('Channel', "SIP/$callerId");
        }
    }
}