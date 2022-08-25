<?php

namespace TaskForce\classes\actions;

class AcceptAction extends AbstractAction
{
    public function getTitle(): string {
        return 'Старт задания';
    }

    public function checkRights($user_id, $customer_id, $performer_id): bool {
        return $user_id === $performer_id;
    }
}
