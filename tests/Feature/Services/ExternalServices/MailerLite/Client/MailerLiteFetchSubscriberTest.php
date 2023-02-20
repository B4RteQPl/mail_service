<?php

namespace Tests\Feature\Services\ExternalServices\MailerLite\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class MailerLiteFetchSubscriberTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_subscriber_is_created_then_can_be_fetched()
    {
        $contact = $this->getNewUser();

        $this->mailerLite()->client->createSubscriber($contact['email']);

        $result = $this->mailerLite()->client->fetchSubscriber($contact['email']);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('groups', $result);
    }
}
