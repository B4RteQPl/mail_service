<?php

namespace Tests\Feature\Services\ExternalServices\MailerLite\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class MailerLiteCreateSubscriberTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function create_subscriber_should_return_new_subscriber()
    {
        $contact = $this->getNewUser();

        $result = $this->mailerLite()->client->createSubscriber($contact['email']);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('groups', $result);
    }
}
