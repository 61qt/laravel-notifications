<?php

namespace QT\Notifications\Channels;

use Illuminate\Notifications\Notification;
use QT\Notifications\Contracts\EloquentMessage;
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
        if (!$notification instanceof EloquentMessage) {
            return;
        }

        $notification->getModel($notifiable)
            ->fill($this->buildPayload($notifiable, $notification))
            ->save();
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
