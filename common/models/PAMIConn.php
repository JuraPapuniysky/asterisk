<?php


namespace common\models;


use PAMI\Client\Impl\ClientImpl;
use yii\base\Component;


class PAMIConn extends Component
{
    public $options;
    public $clientImpl;

    public function init()
    {
        try {

            $this->clientImpl = new ClientImpl($this->options);
            $this->clientImpl->registerEventListener(new EventListener());


        }catch (Exception $e) {
            echo $e->getMessage() . "\n";
       }
    }


}

//  $this->list_command = $this->asterisk->send(new ListCommandsAction());
//     $this->peers = $this->asterisk->send(new SIPPeersAction())->getEvents();
//     $this->commandAction = $this->asterisk->send(new CommandAction($this->command));
// $orig = new OriginateAction('SIP/112');
//  $orig->setContext('default');
//  $orig->setPriority(1);
// $this->originate = $this->asterisk->send($orig);



//$time = time();
// while((time() - $time) < 60) // Wait for events 60. or while(true)
//{
//        usleep(1000); // 1ms delay
// Since we declare(ticks=1) at the top, the following line is not necessary
//        $this->asterisk->process();
// }
//       $this->asterisk->close(); // send logoff and close the connection.
