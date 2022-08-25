<?php

namespace TaskForce\classes\actions;

abstract class AbstractAction
{
    abstract public function getTitle(): string;
    abstract public function getName(): string;
    abstract public function rightsCheck($user_id, $customer_id, $performer_id): bool;
}
