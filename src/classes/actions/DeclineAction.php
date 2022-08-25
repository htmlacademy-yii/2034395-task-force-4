<?php

namespace TaskForce\classes\actions;

class DeclineAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Отказ от задания';
    }

    public function getName(): string {
        return 'decline';
    }

    public function rightsCheck($user_id, $customer_id, $performer_id): bool {
        return $user_id === $performer_id;
    }
}
