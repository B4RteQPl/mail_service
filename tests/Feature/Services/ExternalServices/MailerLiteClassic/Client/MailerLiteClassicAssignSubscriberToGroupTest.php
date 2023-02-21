<?php

namespace Tests\Feature\Services\ExternalServices\MailerLiteClassic\Client;

use Tests\Feature\Services\ExternalServices\MailerLiteClassic\MailerLiteClassicTestCase;

class MailerLiteClassicAssignSubscriberToGroupTest extends MailerLiteClassicTestCase
{

    /**
     * @test
     */
    public function when_subscriber_is_created_then_can_be_assigned_to_group()
    {
        $contact = $this->getNewUser();

        $group = $this->mailerLiteClassic()->client->getListAllGroups()[0];
        $subscriberId = $this->mailerLiteClassic()->client->createSubscriber($contact['email'])['id'];

        $result = $this->mailerLiteClassic()->client->assignSubscriberToGroup($subscriberId, $group['name']);

        $this->assertIsArray($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);

        // verify and clean after test
        $this->assertSubscriberHasGroup($contact['email'], $group['id']);
        $this->deleteSubscriber($subscriberId);
    }
}
