<?php

namespace common\amiactions;


use PAMI\Message\Action\ActionMessage;

class ConfbridgeSetSingleVideoSrc extends ActionMessage
{
    public function __construct($conference, $channel)
    {
        parent::__construct('ConfbridgeSetSingleVideoSrc');
        $this->setKey('Conference', $conference);
        $this->setKey('Channel', $channel);
    }
}