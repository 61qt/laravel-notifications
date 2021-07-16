<?php

namespace QT\Notifications\Channels;

use Illuminate\Support\Arr;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Overtrue\EasySms\Contracts\MessageInterface;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class SmsChannel extends DatabaseChannel
{
    /**
     * The easy sms sdk.
     *
     * @var EasySms
     */
    protected $app;

    /**
     * Create a new Slack channel instance.
     *
     * @param  EasyDingTalkApplication $app
     * @return void
     */
    public function __construct(EasySms $sms)
    {
        $this->app = $sms;
    }

    /**
     * 发送sms消息
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (empty($this->app)) {
            return;
        }

        if (!$notifiable instanceof MessageInterface) {
            return;
        }

        // 尝试获取手机号码
        if (is_object($notifiable) && method_exists($notifiable, 'routeNotificationFor')) {
            $phone = $notifiable->routeNotificationFor('phone', $notification);
        } elseif (is_string($notifiable)) {
            $phone = $notifiable;
        }

        if (empty($phone)) {
            return;
        }

        try {
            $this->app->send($phone, $notification);
        } catch (NoGatewayAvailableException $e) {
            $errors = $e->getExceptions();

            if (count($errors) === 1) {
                throw Arr::first($errors);
            }

            foreach ($errors as $error) {
                Log::error($error);
            }

            throw $e;
        }
    }
}
