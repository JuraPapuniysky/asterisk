<?php


namespace common\models;


use PAMI\Message\Action\ConfbridgeMuteAction;
use PAMI\Message\Action\ConfbridgeUnmuteAction;
use yii\base\Model;

class ConferenceUser extends Model
{
    public $id;

    public $conference;

    public $name;

    public $channel;

    public $callerId;

    public $mutted = false;

    public $video = true;

    public function __construct($conference, $channel, $user, $mutted = false, $video = true)
    {
        $this->conference = $conference;
        $this->channel = $channel;
        $this->callerId = $this->getCallerId($user);
        $this->mutted = $mutted;
        $this->video = $video;
        if($mutted == true)
        {
            $this->muteUser();
        }else if($mutted = false)
        {
            $this->unmuteUser();
        }
    }

    public function muteUser()
    {
        \Yii::$app->pamiconn->clientImpl->send(new ConfbridgeMuteAction($this->conference,$this->channel));
    }

    public function unmuteUser()
    {
        \Yii::$app->pamiconn->clientImpl->send(new ConfbridgeUnmuteAction($this->conference,$this->channel));
    }

    public function videoOffUser()
    {
        $this->video = false;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getCallerId($user)
    {
        return preg_replace("#[^0-9\-]*#is", "", substr(trim($user), -6));
    }
}