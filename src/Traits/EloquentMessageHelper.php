<?php

namespace QT\Notifications\Traits;

use RuntimeException;
use Illuminate\Database\Eloquent\Model;

trait EloquentMessageHelper
{
    /**
     * 获取插入的model
     *
     * @param $notifiable
     * @return Model
     */
    public function getModel($notifiable): Model
    {
        if (!is_object($notifiable) || !method_exists($notifiable, 'routeNotificationFor')) {
            throw new RuntimeException('无法获取通知的model');
        }

        $model = $notifiable->routeNotificationFor('model', $this);

        if (is_string($model) && class_exists($model)) {
            $model = new $model;
        }

        if (!is_object($model) || !$model instanceof Model) {
            throw new RuntimeException('通过eloquent生成通知必须指定一个具体的model');
        }

        return $model;
    }
}
