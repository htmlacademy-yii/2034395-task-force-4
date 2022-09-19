<?php

namespace TaskForce\classes\actions;

class CreateAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Добавление задания';
    }

    public function checkRights(int $userId, int $customerId, int $performerId): bool {
        return $userId === $customerId;
    }
}
