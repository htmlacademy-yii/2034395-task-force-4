<?php

namespace TaskForce\classes\entity;

use TaskForce\classes\actions\AbstractAction;
use TaskForce\classes\actions\CreateAction;
use TaskForce\classes\actions\CancelAction;
use TaskForce\classes\actions\AcceptAction;
use TaskForce\classes\actions\DeclineAction;
use TaskForce\classes\actions\EndAction;
use TaskForce\classes\exceptions\WrongStatusException;
use TaskForce\classes\exceptions\WrongActionException;

class Task
{
    const STATUS_UNDEFINED = 'undefined';
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_WORK = 'in work';
    const STATUS_PERFORMED = 'performed';
    const STATUS_FAILED = 'failed';

    const STATUS_MAP = [
        self::STATUS_UNDEFINED => 'Не создано',
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_IN_WORK => 'На исполнении',
        self::STATUS_PERFORMED => 'Завершено',
        self::STATUS_FAILED => 'Провалено',
    ];

    const ACTIONS_MAP = [
        self::STATUS_UNDEFINED => [
            CreateAction::class
        ],
        self::STATUS_NEW => [
            AcceptAction::class,
            CancelAction::class
        ],
        self::STATUS_IN_WORK => [
            DeclineAction::class,
            EndAction::class
        ],
    ];

    public string $status = self::STATUS_UNDEFINED;
    private int $userId;
    private int $customerId;
    private int $performerId;

    /**
     * @param int $userId
     * @param int $customerId
     * @param int $performerId
     * @param string $status
     * @throws WrongStatusException
     */
    public function __construct(string $status, int $userId, int $customerId, int $performerId = 0)
    {
        $this->userId = $userId;
        $this->customerId = $customerId;
        $this->performerId = $performerId;

        if (!self::STATUS_MAP[$status]) {
            throw new WrongStatusException("Передано несуществующее имя статуса <br>");
        }

        $this->status = $status;
    }

    /**
     * Переданное действие возвращает статус, который получит задание при его выполнении.
     * @param AbstractAction $AbstractAction
     * @return string
     * @throws WrongActionException
     */
    public function getNextStatus(AbstractAction $AbstractAction): string
    {
        return match ($AbstractAction::class) {
            CreateAction::class => self::STATUS_NEW,
            CancelAction::class => self::STATUS_CANCELED,
            AcceptAction::class => self::STATUS_IN_WORK,
            DeclineAction::class => self::STATUS_FAILED,
            EndAction::class => self::STATUS_PERFORMED,
            default => throw new WrongActionException('Передано несуществующее действие'),
        };
    }

    /**
     * Возвращает список возможных действий для текущего статуса задания, в зависимости от прав пользователя.
     * @return array
     */
    public function getAvailableActions(): array
    {
        $availableActionsList = self::ACTIONS_MAP[$this->status] ?? [];
        $result = [];

        foreach ($availableActionsList as $AbstractAction) {
            $action = new $AbstractAction();

            if ($action->checkRights($this->userId, $this->customerId, $this->performerId)) {
                $result[] = $AbstractAction;
            }
        }

        return $result;
    }
}
