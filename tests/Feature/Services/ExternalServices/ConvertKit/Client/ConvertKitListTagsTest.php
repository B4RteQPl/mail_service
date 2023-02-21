<?php

namespace Tests\Feature\Services\ExternalServices\ConvertKit\Client;

use Tests\Feature\Services\ExternalServices\ConvertKit\ConvertKitTestCase;

class ConvertKitListTagsTest extends ConvertKitTestCase
{

    /**
     * @test
     */
    public function listTags_should_return_array_of_tags()
    {
        $tags = $this->convertKit()->client->listTags();

        $this->assertIsArray($tags);
        $this->assertNotEmpty($tags);
        $this->assertCount(2, $tags);

        $this->assertArrayHasKey('id', $tags[0]);
        $this->assertArrayHasKey('name', $tags[0]);

        $this->assertArrayHasKey('id', $tags[1]);
        $this->assertArrayHasKey('name', $tags[1]);
    }
}
