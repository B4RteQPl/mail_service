<?php

namespace Tests\Feature\Services\ExternalServices\MailerLiteClassic\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class MailerLiteClassicAssignSubscriberToGroupTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_subscriber_is_created_then_can_be_assiged_to_group()
    {
        $contact = $this->getNewUser();

        $groupId = $this->mailerLiteClassic()->client->getListAllGroups()[0]['id'];
        $subscriberId = $this->mailerLiteClassic()->client->createSubscriber($contact['email'])['id'];

        $result = $this->mailerLiteClassic()->client->assignSubscriberToGroup($subscriberId, $groupId);

        $this->assertIsArray($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
    }
}
