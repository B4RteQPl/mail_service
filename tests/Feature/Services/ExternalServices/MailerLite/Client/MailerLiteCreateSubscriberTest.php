<?php

namespace Tests\Feature\Services\ExternalServices\MailerLite\Client;

use Tests\Feature\Services\ExternalServices\MailerLite\MailerLiteTestCase;

class MailerLiteCreateSubscriberTest extends MailerLiteTestCase
{

    /**
     * @test
     */
    public function create_subscriber_should_return_new_subscriber()
    {
        $contact = $this->getNewUser();

        $subscriber = $this->mailerLite()->client->createSubscriber($contact['email']);

        $this->assertIsArray($subscriber);
        $this->assertNotEmpty($subscriber);

        $this->assertArrayHasKey('id', $subscriber);
        $this->assertArrayHasKey('email', $subscriber);
        $this->assertArrayHasKey('status', $subscriber);
        $this->assertArrayHasKey('groups', $subscriber);

        $this->deleteSubscriber($subscriber['id']);
    }
}
