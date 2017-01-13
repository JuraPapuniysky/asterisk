<?php


namespace common\models;


use common\amiactions\ConfbridgeSetSingleVideoSrc;
use PAMI\Client\Impl\ClientImpl;
use PAMI\Message\Action\CommandAction;
use PAMI\Message\Action\MeetmeListAction;
use PAMI\Message\Action\MeetmeMuteAction;
use PAMI\Message\Action\MeetmeUnmuteAction;
use PAMI\Message\Action\OriginateAction;
use yii\base\Component;
use yii\web\NotFoundHttpException;


class PAMIConn extends Component
{

    public $options;
    public $clientImpl;
    public $generalConference;
    public $context;
    public $confUser;


    /**
     * initialization of AMI connection
     * @throws \PAMI\Client\Exception\ClientException
     */
    public function initAmi()
    {
        try {

            $this->clientImpl = new ClientImpl($this->options);
            $this->clientImpl->registerEventListener(new EventListener());
            $this->clientImpl->open();
        } catch (Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Close AMI connection
     */
    public function closeAMI()
    {
        $this->clientImpl->process();
        $this->clientImpl->close();
    }

    /**
     * Executing command and return info.
     * @param string $command command to executing
     * @return mixed info
     */
    public function commandCLI($command)
    {
        $message = $this->clientImpl->send(new CommandAction($command));
        usleep(1000);
        return $message;
    }



    /**
     * Returnd information about connected users to conference $conference
     * @param $conferese
     * @return array
     */
    public function getConferenceUsers($conferece)
    {
        $message = $this->clientImpl->send(new MeetmeListAction($conferece));
        usleep(1000);
        return $message;
    }

    /**
     * Create the call from asterisk to user $chanell.
     * @param $channel
     * @return mixed
     */
    public function call($conference, $callerId)
    {

        if(substr($callerId, 0, 3) == '894' or substr($callerId, 0, 3) == '865') {
            $originate = new OriginateAction("SIP/$callerId");
            $originate->setCallerId($callerId);
            $originate->setCodecs(['alaw','h264']);
            $originate->setTimeout(10000);
        }elseif (strlen($callerId) == 3 or substr($callerId, 0, 3) != '894'){
            $originate = new OriginateAction("SIP/SOE_193/$callerId");
            $originate->setCodecs(['alaw']);
            $originate->setTimeout(10000);
            $originate->setCallerId($callerId);
        }

        $originate->setContext($this->context);
        $originate->setExtension($conference);
        $originate->setPriority(1);
        usleep(1000);
        return $this->clientImpl->send($originate);

    }

    /**
     * Call to checked users.
     * @param int $conference  conference.
     * @param string $callerids checkd users.
     */
    public function callChecked($conference, $callerids)
    {
        $callerids = explode(',', $callerids);
        array_pop($callerids);
        foreach ($callerids as $callerid)
        {
            $this->call($conference, $callerid);
        }
    }

    /**
     * Realization of MeetmeMute asterisk action.
     * @param $conference
     * @param $user
     * @return mixed
     */
    public function muteUser($conference, $user)
    {
        $message = $this->clientImpl->send(new MeetmeMuteAction($conference, $user));
        usleep(1000);
        return $message;

    }

    /**
     * Realization of MeetmeUnmute asterisk action.
     * @param $conference
     * @param $user
     * @return mixed
     */
    public function unmuteUser($conference, $user)
    {
        $message = $this->clientImpl->send(new MeetmeUnmuteAction($conference, $user));
        usleep(1000);
        return $message;
    }

    /**
     * Realization of MeetmeList AMI action.
     * @param $conference
     * @return mixed
     */
    public function meetMeList($conference)
    {
        $message = $this->clientImpl->send(new MeetmeListAction($conference));
        usleep(1000);
        return $message;
    }

    /**
     * Realization of ConfbridgeSetSingleVideoSrc AMI action.
     * @param $conference
     * @param $channel
     * @return mixed
     */
    public function setSingleVideo($conference, $channel)
    {
        $message = $this->clientImpl->send(new ConfbridgeSetSingleVideoSrc($conference, $channel));
        usleep(1000);
        return $message;
    }

    public function groupCall($conference, Array $callerIds)
    {
        $originate = new Originate($callerIds);
        //$originate->setCallerId('000');
        $originate->setCodecs(['alaw','h264']);
        $originate->setTimeout(10000);
        $originate->setContext($this->context);
        $originate->setExtension($conference);
        $originate->setPriority(1);
        usleep(1000);
        return $this->clientImpl->send($originate);
    }


}
