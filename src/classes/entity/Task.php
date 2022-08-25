<?php

namespace TaskForce\classes\entity;

use TaskForce\classes\actions\CreateAction;
use TaskForce\classes\actions\CancelAction;
use TaskForce\classes\actions\AcceptAction;
use TaskForce\classes\actions\DeclineAction;
use TaskForce\classes\actions\EndAction;

class Task
{
    const STATUS_UNDEFINED = 'undefined';
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_WORK = 'in work';
    const STATUS_PERFORMED = 'performed';
    const STATUS_FAILED = 'failed';

    const TASK_MAP = [
        self::STATUS_UNDEFINED => 'Не создано',
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_IN_WORK => 'На исполнении',
        self::STATUS_PERFORMED => 'Завершено',
        self::STATUS_FAILED => 'Провалено',
    ];

    private object $action_create;
    private object $action_cancel;
    private object $action_accept;
    private object $action_decline;
    private object $action_end;

    public string $status = self::STATUS_NEW;
    private int $user_id = 1;
    private int $customer_id = 1;
    private int $performer_id = 2;

    public function __construct($user_id, $customer_id, $performer_id)
    {
        $this->action_create = new CreateAction();
        $this->action_cancel = new CancelAction();
        $this->action_accept = new AcceptAction();
        $this->action_decline = new DeclineAction();
        $this->action_end = new EndAction();
        $this->user_id = $user_id;
        $this->customer_id = $customer_id;
        $this->performer_id = $performer_id;
    }

    public function getNextStatus($action): string
    {
        return match ($action) {
            $this->action_create->getName() => self::STATUS_NEW,
            $this->action_cancel->getName() => self::STATUS_CANCELED,
            $this->action_accept->getName() => self::STATUS_IN_WORK,
            $this->action_decline->getName() => self::STATUS_FAILED,
            $this->action_end->getName() => self::STATUS_PERFORMED,
            default => '',
        };
    }

    public function getAvailableActions(): array
    {
        return match($this->status) {
            self::STATUS_UNDEFINED => [
                $this->action_create->rightsCheck($this->user_id, $this->customer_id, $this->performer_id) ? $this->action_create : null
            ],
            self::STATUS_NEW => [
                $this->action_accept->rightsCheck($this->user_id, $this->customer_id, $this->performer_id) ? $this->action_accept : null,
                $this->action_cancel->rightsCheck($this->user_id, $this->customer_id, $this->performer_id) ? $this->action_cancel : null
            ],
            self::STATUS_IN_WORK => [
                $this->action_decline->rightsCheck($this->user_id, $this->customer_id, $this->performer_id) ? $this->action_decline : null,
                $this->action_end->rightsCheck($this->user_id, $this->customer_id, $this->performer_id) ? $this->action_end : null
            ],
            default => []
        };
    }
}
