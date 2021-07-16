<?php

namespace QT\Notifications\Channels;

use RuntimeException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\DatabaseChannel;

class EloquentChannel extends DatabaseChannel
{
    /**
     * 通过eloquent生成通知
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $model = $notifiable->routeNotificationFor('model', $notification);

        if (is_string($model) && class_exists($model)) {
            $model = new $model;
        }

        if (!is_object($model) || !$model instanceof Model) {
            throw new RuntimeException('通过eloquent生成通知必须指定一个具体的model');
        }

        return $model::create($this->buildPayload($notifiable, $notification));
    }

    /**
     * 构造数据结构
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array
     */
    protected function buildPayload($notifiable, Notification $notification)
    {
        return $this->getData($notifiable, $notification);
    }
}
