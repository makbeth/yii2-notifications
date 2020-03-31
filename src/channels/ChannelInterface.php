<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace makbeth\notifications\channels;

use makbeth\notifications\NotifiableInterface;
use makbeth\notifications\NotificationInterface;

interface ChannelInterface
{
    /**
     * @param NotifiableInterface $recipient
     * @param NotificationInterface $notification
     * @return mixed channel response
     * @throws \Exception
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification);
}