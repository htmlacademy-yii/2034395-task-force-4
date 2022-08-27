<?php

namespace TaskForce\classes\actions;

class EndAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Завершения задания';
    }

    public function checkRights(int $userId, int $customerId, int $performerId): bool {
        return $userId === $performerId;
    }
}
