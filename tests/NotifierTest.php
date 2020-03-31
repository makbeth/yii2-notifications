<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace makbeth\notifications\tests;

use makbeth\notifications\events\NotificationEvent;
use makbeth\notifications\Notifier;

class NotifierTest extends TestCase
{
    /**
     * @var $notifier Notifier
     */
    protected $notifier;

    protected function setUp()
    {
        parent::setUp();
        $this->notifier = \Yii::createObject([
            'class' => Notifier::className(),
            'channels' => [
                'mockChannel' => $this->createMock('makbeth\notifications\channels\ChannelInterface')
            ]
        ]);
    }


    public function testSend()
    {
        $notification = $this->createMock('makbeth\notifications\NotificationInterface');
        $notification->method('broadcastOn')->willReturn(['mockChannel']);
        
        $recipient = $this->createMock('makbeth\notifications\NotifiableInterface');
        $recipient->method('shouldReceiveNotification')->willReturn(true);
        $recipient->method('viaChannels')->willReturn(['mockChannel']);
        
        $this->notifier->channels['mockChannel']
            ->expects($this->once())
            ->method('send')
            ->with($recipient, $notification);

        $eventRaised = null;
        $this->notifier->on(Notifier::EVENT_AFTER_SEND, function(NotificationEvent $event) use (&$eventRaised) {
            $eventRaised = $event;
        });

        $this->notifier->send($recipient, $notification);
        $this->assertNotEmpty($eventRaised);
        $this->assertInstanceOf('makbeth\notifications\NotificationInterface', $eventRaised->notification);
        $this->assertInstanceOf('makbeth\notifications\NotifiableInterface', $eventRaised->recipient);
        $this->assertEquals('mockChannel', $eventRaised->channel);
    }
}
