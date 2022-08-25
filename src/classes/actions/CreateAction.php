<?php

namespace TaskForce\classes\actions;

class CreateAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Добавление задания';
    }

    public function checkRights($user_id, $customer_id, $performer_id): bool {
        return $user_id === $customer_id;
    }
}
