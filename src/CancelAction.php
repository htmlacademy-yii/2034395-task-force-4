<?php

namespace TaskForce\classes\actions;

class CancelAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Отмена задания';
    }

    public function checkRights(int $userId, int $customerId, int $performerId): bool {
        return $userId === $customerId;
    }
}
