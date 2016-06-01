<?php


namespace common\models;


use PAMI\Client\Impl\ClientImpl;
use PAMI\Message\Action\MeetmeListAction;
use PAMI\Message\Action\MeetmeMuteAction;
use PAMI\Message\Action\MeetmeUnmuteAction;
use PAMI\Message\Action\OriginateAction;
use yii\base\Component;


class PAMIConn extends Component
{
    public $options = [
        'host' => '10.109.33.150',
        'port' => '5038',
        'username' => 'admin',
        'secret' => 'admin',
        'connect_timeout' => 5000,
        'read_timeout' => 5000,
        'scheme' => 'tcp://',// try tls://
    ];
    public $clientImpl;
    public $generalConference;


    /**
     * initialization of AMI connection
     * @throws \PAMI\Client\Exception\ClientException
     */
    public function init()
    {
        try {

            $this->clientImpl = new ClientImpl($this->options);
            $this->clientImpl->registerEventListener(new EventListener());
            $this->clientImpl->open();



        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }



    /**
     * Returnd information about connected users to conference $conference
     * @param $conferese
     * @return array
     */
    public function getConferenceUsers($conferese)
    {
        $message = $this->clientImpl->send(new MeetmeListAction($conferese));
        usleep(1000);
        return $message->getEvents();
    }

    /**
     * Create the call from asterisk yo user $chanell
     * @param $channel
     * @return mixed
     */
    public function call($channel)
    {
        $originate = new OriginateAction($channel);
        $originate->setContext('default');
        $originate->setPriority(1);
        usleep(1000);
        return $this->clientImpl->send($originate);

    }

    /**
     * Realization of MeetmeMute asterisk action
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
     * Realization of MeetmeUnmute asterisk action
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


}
