<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace makbeth\notifications\events;


use makbeth\notifications\NotifiableInterface;
use makbeth\notifications\NotificationInterface;
use yii\base\Event;

class NotificationEvent extends Event
{
    /**
     * @var NotificationInterface
     */
    public $notification;

    /**
     * @var NotifiableInterface
     */
    public $recipient;

    /**
     * @var string
     */
    public $channel;

    /**
     * @var mixed
     */
    public $response;
}