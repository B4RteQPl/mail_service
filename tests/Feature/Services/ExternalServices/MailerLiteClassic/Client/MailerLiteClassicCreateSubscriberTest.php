<?php

namespace Tests\Feature\Services\ExternalServices\MailerLiteClassic\Client;

use Tests\Feature\Services\ExternalServices\MailerLiteClassic\MailerLiteClassicTestCase;

class MailerLiteClassicCreateSubscriberTest extends MailerLiteClassicTestCase
{

    /**
     * @test
     */
    public function when_create_subscriber_should_return_subscriber_data()
    {
        $contact = $this->getNewUser();

        $subscriber = $this->mailerLiteClassic()->client->createSubscriber($contact['email']);

        $this->assertIsArray($subscriber);
        $this->assertNotEmpty($subscriber);

        $this->assertArrayHasKey('id', $subscriber);
        $this->assertArrayHasKey('email', $subscriber);

        $this->deleteSubscriber($subscriber['id']);
    }
}
