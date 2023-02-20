<?php

namespace Tests\Feature\Services\ExternalServices\MailerLiteClassic\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class MailerLiteClassicUnAssignSubscriberFromGroupTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_remove_group_from_subscriber_then_subscriber_is_removed_from_group()
    {
        $contact = $this->getNewUser();

        $groupId = $this->mailerLiteClassic()->client->getListAllGroups()[0]['id'];
        $subscriberId = $this->mailerLiteClassic()->client->createSubscriber($contact['email'])['id'];

        $this->mailerLiteClassic()->client->assignSubscriberToGroup($subscriberId, $groupId);

        $isUnAssigned = $this->mailerLiteClassic()->client->unAssignSubscriberFromGroup($subscriberId, $groupId);

        $this->assertTrue($isUnAssigned);
    }
}
