<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace makbeth\notifications\tests;


use makbeth\notifications\channels\TwilioChannel;
use makbeth\notifications\messages\SmsMessage;
use yii\httpclient\Client;
use yii\httpclient\Request;

class TwilioChannelTest extends TestCase
{

    public function testSend()
    {
        $recipient = $this->createMock('makbeth\notifications\NotifiableInterface');
        $recipient->expects($this->once())
            ->method('routeNotificationFor')
            ->with('sms')
            ->willReturn('+1234567890');
        
        $client = $this->createMock(Client::className());
        $client->expects($this->once())
            ->method('send');
        $client->method('createRequest')
            ->willReturn(\Yii::createObject([
                'class' => Request::className(),
                'client' => $client
            ]));
        
        $channel = \Yii::createObject([
            'class' => TwilioChannel::className(),
            'from' => '+1234567890123',
            'httpClient' => $client
        ]);

        $notification = $this->createMock('makbeth\notifications\NotificationInterface');
        $notification->expects($this->once())
            ->method('exportFor')
            ->with('sms')
            ->willReturn(\Yii::createObject([
                'class' => SmsMessage::className(),
                'body' => 'Test message body',
            ]));
        $channel->send($recipient, $notification);

    }
}