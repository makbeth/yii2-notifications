<?php
/**
 * @copyright Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace makbeth\notifications\channels;


use makbeth\notifications\messages\DatabaseMessage;
use makbeth\notifications\NotifiableInterface;
use makbeth\notifications\NotificationInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\helpers\Json;

class ActiveRecordChannel extends Component implements ChannelInterface
{
    /**
     * @var BaseActiveRecord|string
     */
    public $model = 'makbeth\notifications\models\Notification';

    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        $model = \Yii::createObject($this->model);

        if (!$model instanceof BaseActiveRecord) {
            throw new InvalidConfigException('Model class must extend from \\yii\\db\\BaseActiveRecord');
        }

        /** @var DatabaseMessage $message */
        $message = $notification->exportFor('database');
        list($notifiableType, $notifiableId) = $recipient->routeNotificationFor('database');
        $data = [
            'level' => $message->level,
            'subject' => $message->subject,
            'body' => $message->body,
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
            'data' => Json::encode($message->data),
        ];

        if ($model->load($data, '')) {
            return $model->insert();
        }

        return false;
    }
}