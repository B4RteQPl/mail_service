<?php

namespace Tests\Feature\Services\ExternalServices\MailerLiteClassic\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class MailerLiteClassicFetchSubscriberTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_subscriber_is_created_then_can_be_fetched()
    {
        $contact = $this->getNewUser();

        $this->mailerLiteClassic()->client->createSubscriber($contact['email']);

        $result = $this->mailerLiteClassic()->client->fetchSubscriber($contact['email']);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email', $result);
    }
}
