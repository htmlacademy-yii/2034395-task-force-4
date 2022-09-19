<?php

namespace TaskForce\classes\actions;

abstract class AbstractAction
{
    abstract public function getTitle(): string;
    abstract public function checkRights(int $userId, int $customerId, int $performerId): bool;
}
