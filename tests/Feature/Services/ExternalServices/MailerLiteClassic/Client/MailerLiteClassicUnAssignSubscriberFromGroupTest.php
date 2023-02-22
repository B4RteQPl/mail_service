<?php

namespace Tests\Feature\Services\ExternalServices\MailerLiteClassic\Client;

use Tests\Feature\Services\ExternalServices\MailerLiteClassic\MailerLiteClassicTestCase;

class MailerLiteClassicUnAssignSubscriberFromGroupTest extends MailerLiteClassicTestCase
{

    /**
     * @test
     */
    public function when_remove_group_from_subscriber_then_subscriber_is_removed_from_group()
    {
        $contact = $this->getNewUser();

        $group = $this->mailerLiteClassic()->client->getListAllGroups()[0];
        $subscriberId = $this->mailerLiteClassic()->client->createSubscriber($contact['email'])['id'];

        $this->mailerLiteClassic()->client->assignSubscriberToGroup($subscriberId, $group['name']);

        $isUnAssigned = $this->mailerLiteClassic()->client->unAssignSubscriberFromGroup($subscriberId, $group['id']);

        $this->assertTrue($isUnAssigned);

        // verify and clean after test
        $this->deleteSubscriber($subscriberId);
    }
}
