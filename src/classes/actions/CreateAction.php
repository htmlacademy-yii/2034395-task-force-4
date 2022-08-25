<?php

namespace TaskForce\classes\actions;

class CreateAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Добавление задания';
    }

    public function getName(): string {
        return 'new';
    }

    public function rightsCheck($user_id, $customer_id, $performer_id): bool {
        return $user_id === $customer_id;
    }
}
