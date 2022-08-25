<?php

namespace TaskForce\classes\actions;

class CancelAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Отмена задания';
    }

    public function getName(): string {
        return 'cancel';
    }

    public function rightsCheck($user_id, $customer_id, $performer_id): bool {
        return $user_id === $customer_id;
    }
}
