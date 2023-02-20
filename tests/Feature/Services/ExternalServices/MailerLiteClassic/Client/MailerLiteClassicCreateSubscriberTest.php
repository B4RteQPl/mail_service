<?php

namespace Tests\Feature\Services\ExternalServices\MailerLiteClassic\Client;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class MailerLiteClassicCreateSubscriberTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_create_subscriber_should_return_subscriber_data()
    {
        $contact = $this->getNewUser();

        $result = $this->mailerLiteClassic()->client->createSubscriber($contact['email']);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email', $result);
    }
}
