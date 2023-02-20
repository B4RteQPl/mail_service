<?php

namespace App\Services\ExternalServices\Mailchimp\Commands\ListMembers;

use App\Services\ExternalServices\Mailchimp\Commands\AbstractCommand;
use App\ValueObjects\Email;

class MailchimpCommandGetListMemberInfo extends AbstractCommand
{
    public function execute(array $params)
    {
        $email = new Email($params['email']);
        $listId = $params['list_id'];

        return $this->client->getListMemberInfo($email, $listId);
    }

    public function getConfig()
    {
        return [
            'title' => [
                'pl' => '',
                'en' => '',
            ],
            'description' => [
                'pl' => '',
                'en' => '',
            ],
            'parameters' => [

            ],
        ];
    }
}
