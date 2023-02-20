<?php

namespace Tests\Feature\Services\ExternalServices\ConvertKit\Client;

use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class ConvertKitListSubscribers extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function list_subscribers_should_return_contact_found_by_email()
    {
        $contact = $this->getNewUser();

        $tagId = $this->convertKit()->client->listTags()[0]['id'];
        $this->convertKit()->client->tagSubscriber($contact['email'], $tagId);

        $result = $this->convertKit()->client->listSubscribers($contact['email']);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email_address', $result);
        $this->assertArrayHasKey('first_name', $result);
    }

    /**
     * @test
     */
    public function list_subscribers_should_return_empty_array_if_subscriber_not_found()
    {
        $contact = $this->getNewUser();

        $result = $this->convertKit()->client->listSubscribers($contact['email']);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
