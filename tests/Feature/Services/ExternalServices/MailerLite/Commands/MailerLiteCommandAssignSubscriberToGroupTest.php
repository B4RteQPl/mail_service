<?php

namespace Tests\Feature\Services\ExternalServices\MailerLite\Commands;

use Tests\Feature\Services\ExternalServices\MailerLite\MailerLiteTestCase;

class MailerLiteCommandAssignSubscriberToGroupTest extends MailerLiteTestCase
{

    /**
     * @test
     */
    public function do_()
    {
        $groupId = $this->mailerLite()->client->getListAllGroups()[0]['id'];
        $email = $this->getUniqueEmail();

        $result = $this->mailerLite()->assignSubscriberToGroup->execute([
            'email' => $email,
            'groupId' => $groupId
        ]);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('name', $result);
    }
}
