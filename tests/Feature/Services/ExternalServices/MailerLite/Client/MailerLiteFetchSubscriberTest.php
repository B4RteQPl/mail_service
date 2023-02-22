<?php

namespace Tests\Feature\Services\ExternalServices\MailerLite\Client;

use Tests\Feature\Services\ExternalServices\MailerLite\MailerLiteTestCase;

class MailerLiteFetchSubscriberTest extends MailerLiteTestCase
{

    /**
     * @test
     */
    public function when_subscriber_is_created_then_can_be_fetched()
    {
        $contact = $this->getNewUser();

        $this->mailerLite()->client->createSubscriber($contact['email']);

        $subscriber = $this->mailerLite()->client->fetchSubscriber($contact['email']);

        $this->assertArrayHasKey('id', $subscriber);
        $this->assertArrayHasKey('email', $subscriber);
        $this->assertArrayHasKey('status', $subscriber);
        $this->assertArrayHasKey('groups', $subscriber);

        $this->deleteSubscriber($subscriber['id']);
    }
}
