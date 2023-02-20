<?php

namespace Tests\Feature\Services\ExternalServices\Sendgrid\Client;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class SendgridAddContactToListTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_subscriber_is_created_then_can_be_assigned_to_group()
    {
        $contact = $this->getNewUser();
        $listId = $this->sendgrid()->client->getAllLists()[0]['id'];

        $result =  $this->sendgrid()->client->addContactToList($contact['email'], $listId);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('job_id', $result);
    }
}
