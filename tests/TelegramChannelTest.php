<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace makbeth\notifications\tests;


use makbeth\notifications\channels\TelegramChannel;
use makbeth\notifications\messages\TelegramMessage;
use yii\httpclient\Client;
use yii\httpclient\Request;

class TelegramChannelTest extends TestCase
{
    public function testSend()
    {
        $recipient = $this->createMock('makbeth\notifications\NotifiableInterface');
        $recipient->expects($this->once())
            ->method('routeNotificationFor')
            ->with('telegram')
            ->willReturn('126305629');

        $client = $this->createMock(Client::className());
        $client->expects($this->once())
            ->method('send');
        $client->method('createRequest')
            ->willReturn(\Yii::createObject([
                'class' => Request::className(),
                'client' => $client
            ]));


        $channel = \Yii::createObject([
            'class' => TelegramChannel::className(),
            'botToken' => 'dummy',
            'httpClient' => $client
        ]);

        $notification = $this->createMock('makbeth\notifications\NotificationInterface');
        $notification->expects($this->once())
            ->method('exportFor')
            ->with('telegram')
            ->willReturn(\Yii::createObject([
                'class' => TelegramMessage::className(),
                'subject' => 'Good stuff',
                'body' => 'Test message body',
            ]));
        /** @var \yii\httpclient\Response $resp */
        $channel->send($recipient, $notification);
    }
}