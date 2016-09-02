<?php
return [
    
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=asterisk',
            'username' => 'root',
            'password' => '012345',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'pamiconn' => [
            'class' => 'common\models\PAMIConn',
            'options' => [
                'host' => '10.109.71.196',
                'port' => '5038',
                'username' => 'bos',
                'secret' => 'Bthfh[bZ',
                'connect_timeout' => 50000,
                'read_timeout' => 50000,
                'scheme' => 'tcp://',// try tls://
            ],
            'generalConference' => '000',
            'context' => 'from-internal',
        ],

        'pamicall' => [
            'class' => 'common\models\PAMIConn',
            'options' => [
                'host' => '10.109.36.193',
                'port' => '5038',
                'username' => 'openmeetings',
                'secret' => '12345',
                'connect_timeout' => 50000,
                'read_timeout' => 50000,
                'scheme' => 'tcp://',// try tls://
            ],
            'generalConference' => '000',
            'context' => 'remote',
        ],
        

    ],
];
