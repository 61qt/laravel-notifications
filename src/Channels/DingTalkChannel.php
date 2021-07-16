<?php

namespace QT\Notifications\Channels;

use RuntimeException;
use Illuminate\Notifications\Notification;
use QT\Notifications\Contracts\DingTalkMessage;
use EasyDingTalk\Application as EasyDingTalkApplication;

class DingTalkChannel
{
    /**
     * The DingTalk Sdk instance.
     *
     * @var EasyDingTalkApplication
     */
    protected $app;

    /**
     * Create a new Slack channel instance.
     *
     * @param  EasyDingTalkApplication $app
     * @return void
     */
    public function __construct(EasyDingTalkApplication $app = null)
    {
        $this->app = $app;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$notification instanceof DingTalkMessage) {
            return;
        }

        $app = $notification->getApplication() ?: $this->app;

        if ($app === null) {
            return;
        }

        $agentId = $notification->getAgentId();
        $message = $notification->getMessage($notifiable);
        // 尝试获取ding id
        if (is_object($notifiable) && method_exists($notifiable, 'routeNotificationFor')) {
            $toUsers = $notifiable->routeNotificationFor('ding_id', $notification);
        } elseif (is_string($notifiable)) {
            $toUsers = $notifiable;
        }

        if (empty($agentId) || empty($toUsers)) {
            return;
        }

        $response = $app->messages->send($agentId, $toUsers, '', $message);

        if (!empty($response['errcode']) && $response['errcode'] != 0) {
            throw new RuntimeException($response['errmsg'], $response['errcode']);
        }
    }
}
