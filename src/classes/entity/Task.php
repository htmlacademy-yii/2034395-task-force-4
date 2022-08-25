<?php

namespace TaskForce\classes\entity;

use TaskForce\classes\actions\AbstractAction;
use TaskForce\classes\actions\CreateAction;
use TaskForce\classes\actions\CancelAction;
use TaskForce\classes\actions\AcceptAction;
use TaskForce\classes\actions\DeclineAction;
use TaskForce\classes\actions\EndAction;

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_WORK = 'in work';
    const STATUS_PERFORMED = 'performed';
    const STATUS_FAILED = 'failed';

    const TASK_MAP = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_IN_WORK => 'На исполнении',
        self::STATUS_PERFORMED => 'Завершено',
        self::STATUS_FAILED => 'Провалено',
    ];

    const ACTIONS_MAP = [
        self::STATUS_NEW => [
            AcceptAction::class,
            CancelAction::class
        ],
        self::STATUS_IN_WORK => [
            DeclineAction::class,
            EndAction::class
        ],
    ];

    public string $status = self::STATUS_NEW;
    private int $user_id;
    private int $customer_id;
    private int $performer_id;

    public function __construct($user_id, $customer_id, $performer_id)
    {
        $this->user_id = $user_id;
        $this->customer_id = $customer_id;
        $this->performer_id = $performer_id;
    }

    public function getNextStatus($AbstractAction): string
    {
        return match ($AbstractAction::class) {
            CreateAction::class => self::STATUS_NEW,
            CancelAction::class => self::STATUS_CANCELED,
            AcceptAction::class => self::STATUS_IN_WORK,
            DeclineAction::class => self::STATUS_FAILED,
            EndAction::class => self::STATUS_PERFORMED,
            default => '',
        };
    }

    public function getAvailableActions(): array
    {
        $arr = self::ACTIONS_MAP[$this->status] ?? [];
        $result = [];

        if (count($arr) === 0) {
            return [];
        }

        foreach ($arr as $AbstractAction) {
            $action = new $AbstractAction();

            if ($action->checkRights($this->user_id, $this->customer_id, $this->performer_id)) {
                $result[] = $AbstractAction;
            }
        }

        return $result;
    }
}
