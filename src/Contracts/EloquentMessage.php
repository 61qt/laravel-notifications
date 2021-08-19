<?php

namespace QT\Notifications\Contracts;

use Illuminate\Database\Eloquent\Model;

interface EloquentMessage
{
    /**
     * 获取插入的model
     * 
     * @param $notifiable
     * @return Model
     */
    public function getModel($notifiable) : Model;
}
