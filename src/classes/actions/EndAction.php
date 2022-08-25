<?php

namespace TaskForce\classes\actions;

class EndAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Завершения задания';
    }

    public function getName(): string {
        return 'end';
    }

    public function rightsCheck($user_id, $customer_id, $performer_id): bool {
        return $user_id === $performer_id;
    }
}
