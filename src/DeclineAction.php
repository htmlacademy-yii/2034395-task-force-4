<?php

namespace TaskForce\classes\actions;

class DeclineAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Отказ от задания';
    }

    public function checkRights(int $userId, int $customerId, int $performerId): bool {
        return $userId === $performerId;
    }
}
