<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace makbeth\notifications\tests;


use makbeth\notifications\channels\SlackChannel;
use makbeth\notifications\messages\SlackMessage;
use yii\httpclient\Client;

class SlackChannelTest
{
    public function testSend()
    {
        $recipient = $this->createMock('makbeth\notifications\NotifiableInterface');
        $recipient->expects($this->once())
            ->method('routeNotificationFor')
            ->with('slack')
            ->willReturn('https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX');

        $client = $this->createMock(Client::className());
        $client->expects($this->once())
            ->method('send');
        $client->method('createRequest')
            ->willReturn(\Yii::createObject([
                'class' => Request::className(),
                'client' => $client
            ]));

        $channel = \Yii::createObject([
            'class' => SlackChannel::className(),
            'httpClient' => $client
        ]);

        $notification = $this->createMock('makbeth\notifications\NotificationInterface');
        $notification->expects($this->once())
            ->method('exportFor')
            ->with('slack')
            ->willReturn(\Yii::createObject([
                'class' => SlackMessage::className(),
                'body' => 'Test message body',
            ]));
        $channel->send($recipient, $notification);

    }
}