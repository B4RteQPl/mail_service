<?php

namespace Tests\Feature\Services\ExternalServices\Sendgrid\Client;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\Traits\ExternalServicesProviderTrait;
use Tests\TestCase;

class SendgridGetContactsByEmailTest extends TestCase
{

    use ExternalServicesProviderTrait;

    /**
     * @test
     */
    public function when_contact_is_just_created_then_method_return_empty_array()
    {
        $contact = $this->getNewUser();
        $this->sendgrid()->client->addContact($contact['email']);

        $result = $this->sendgrid()->client->getContactsByEmail($contact['email']);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function when_contact_is_created_then_method_return_contact_array()
    {
        $result = $this->sendgrid()->client->getContactsByEmail(new Email('existing@email.com'));

        if (empty($result)) {
            $this->sendgrid()->client->addContact(new Email('existing@email.com'));
            $this->markTestSkipped('Creating contact in progress, try again later');
        }

        $result = $this->sendgrid()->client->getContactsByEmail(new Email('existing@email.com'));

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('first_name', $result);
        $this->assertArrayHasKey('last_name', $result);
        $this->assertArrayHasKey('list_ids', $result);

    }
}
