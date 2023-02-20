<?php

namespace Tests\Feature\Services\ExternalServices\MailerLite\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class MailerLiteUnAssignSubscriberFromGroupTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_remove_tag_from_subscriber_then_subscriber_tag_is_removed()
    {
        $contact = $this->getNewUser();

        $groupId = $this->mailerLite()->client->getListAllGroups()[0]['id'];
        $subscriberId = $this->mailerLite()->client->createSubscriber($contact['email'])['id'];

        $this->mailerLite()->client->assignSubscriberToGroup($subscriberId, $groupId);

        $isUnAssigned = $this->mailerLite()->client->unAssignSubscriberFromGroup($subscriberId, $groupId);

        $this->assertTrue($isUnAssigned);
    }
}
