<?php

namespace Tests\Feature\Services\ExternalServices\MailerLiteClassic\Client;

use Tests\Feature\Services\ExternalServices\MailerLiteClassic\MailerLiteClassicTestCase;

class MailerLiteClassicFetchSubscriberTest extends MailerLiteClassicTestCase
{

    /**
     * @test
     */
    public function when_subscriber_is_created_then_can_be_fetched()
    {
        $contact = $this->getNewUser();

        $this->mailerLiteClassic()->client->createSubscriber($contact['email']);

        $subscriber = $this->mailerLiteClassic()->client->fetchSubscriber($contact['email']);

        $this->assertArrayHasKey('id', $subscriber);
        $this->assertArrayHasKey('email', $subscriber);

        $this->deleteSubscriber($subscriber['id']);
    }
}
