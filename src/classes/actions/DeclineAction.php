<?php

namespace TaskForce\classes\actions;

class DeclineAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Отказ от задания';
    }

    public function checkRights($user_id, $customer_id, $performer_id): bool {
        return $user_id === $performer_id;
    }
}
