<?php

namespace Tests\Feature\Services\ExternalServices\Sendgrid\Client;

use Tests\Feature\Services\ExternalServices\Sendgrid\SendgridTestCase;

class SendgridAddContactTest extends SendgridTestCase
{

    /**
     * @test
     */
    public function when_add_contact_should_return_contact_data()
    {
        $contact = $this->getNewUser();

        $result = $this->sendgrid()->client->addContact($contact['email']);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('job_id', $result);

    }
}
