<?php

namespace Tests\Feature\Services\ExternalServices\ConvertKit\Client;

use Tests\Feature\Services\ExternalServices\ConvertKit\ConvertKitTestCase;

class ConvertKitTagSubscriberTest extends ConvertKitTestCase
{

    /**
     * @test
     */
    public function when_tag_subscriber_called_for_new_contact_then_new_subscriber_with_tag_is_created()
    {
        $contact = $this->getNewUser();

        $tagId = $this->convertKit()->client->listTags()[0]['id'];
        $result = $this->convertKit()->client->tagSubscriber($contact['email'], $tagId);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('first_name', $result);
        $this->assertArrayHasKey('email_address', $result);
    }
}
