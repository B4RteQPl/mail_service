<?php

namespace Tests\Feature\Services\ExternalServices\ConvertKit\Client;

use Tests\Feature\Services\ExternalServices\ConvertKit\ConvertKitTestCase;

class ConvertKitRemoveTagFromSubscriberTest extends ConvertKitTestCase
{

    /**
     * @test
     */
    public function when_remove_tag_from_subscriber_then_subscriber_tag_is_removed()
    {
        $contact = $this->getNewUser();

        $tagId = $this->convertKit()->client->listTags()[0]['id'];
        $subscriberId = $this->convertKit()->client->tagSubscriber($contact['email'], $tagId)['id'];

        $result = $this->convertKit()->client->removeTagFromSubscriber($subscriberId, $tagId);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('created_at', $result);
    }
}
