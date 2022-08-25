<?php

namespace TaskForce\classes\actions;

class EndAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Завершения задания';
    }

    public function checkRights($user_id, $customer_id, $performer_id): bool {
        return $user_id === $performer_id;
    }
}
