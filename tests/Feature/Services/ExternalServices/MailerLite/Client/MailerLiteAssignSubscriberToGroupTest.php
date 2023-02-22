<?php

namespace Tests\Feature\Services\ExternalServices\MailerLite\Client;

use Tests\Feature\Services\ExternalServices\MailerLite\MailerLiteTestCase;

class MailerLiteAssignSubscriberToGroupTest extends MailerLiteTestCase
{

    /**
     * @test
     */
    public function when_subscriber_is_created_then_can_be_assigned_to_group()
    {
        $contact = $this->getNewUser();

        $groupId = $this->mailerLite()->client->getListAllGroups()[0]['id'];
        $subscriberId = $this->mailerLite()->client->createSubscriber($contact['email'])['id'];

        $result = $this->mailerLite()->client->assignSubscriberToGroup($subscriberId, $groupId);

        $this->assertIsArray($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);

        // verify and clean after test
        $this->assertSubscriberHasGroup($contact['email'], $groupId);
        $this->deleteSubscriber($subscriberId);
    }
}
