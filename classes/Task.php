<?php
class Task {
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_WORK = 'in work';
    const STATUS_PERFORMED = 'performed';
    const STATUS_FAILED = 'failed';

    const ACTION_CREATE = 'create';
    const ACTION_CANCEL = 'cancel';
    const ACTION_ACCEPT = 'accept';
    const ACTION_DECLINE = 'decline';
    const ACTION_END = 'end';

    const TASK_MAP = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_IN_WORK => 'На исполнении',
        self::STATUS_PERFORMED => 'Завершено',
        self::STATUS_FAILED => 'Провалено',
        self::ACTION_CREATE => 'Добавление задания',
        self::ACTION_CANCEL => 'Отмена задания',
        self::ACTION_ACCEPT => 'Старт задания',
        self::ACTION_DECLINE => 'Отказ от задания',
        self::ACTION_END => 'Завершение задания'
    ];

    private $customer_id;
    private $performer_id;

    public string $status = self::STATUS_NEW;

    public function __construct($customer, $performer) {
        $this->customer_id = $customer;
        $this->performer_id = $performer;
    }

    public function getNextStatus($action): string {
        return match ($action) {
            self::ACTION_CREATE => self::STATUS_NEW,
            self::ACTION_CANCEL => self::STATUS_CANCELED,
            self::ACTION_ACCEPT => self::STATUS_IN_WORK,
            self::ACTION_DECLINE => self::STATUS_FAILED,
            self::ACTION_END => self::STATUS_PERFORMED,
            default => 'error',
        };
    }

    public function getAvailableActions(): string|array {
        return match ($this->status) {
            self::STATUS_NEW => [self::ACTION_ACCEPT, self::ACTION_CANCEL],
            self::STATUS_IN_WORK => [self::ACTION_DECLINE, self::ACTION_END],
            default => 'no actions available',
        };
    }
}
