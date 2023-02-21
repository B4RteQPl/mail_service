<?php

namespace Tests\Feature\Services\ExternalServices\MailerLiteClassic;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\ExternalServicesTestCase;

abstract class MailerLiteClassicTestCase extends ExternalServicesTestCase
{
    public function mailerLiteClassic()
    {
        return $this->externalServices()->mailerLiteClassic->setClient(env('TEST_MAILERLITE_CLASSIC_API_KEY'));
    }

    protected function assertSubscriberHasGroup(Email $email, int $groupId)
    {
        $subscriber = $this->mailerLiteClassic()->client->fetchSubscriber($email, $groupId);

        $this->assertIsArray($subscriber);
        $this->assertArrayHasKey('groups', $subscriber);
        $this->assertCount(1, $subscriber['groups']);
        $this->assertEquals($groupId, $subscriber['groups'][0]);
    }

    protected function deleteSubscriber(string $subscriberId)
    {
        $isDeleted = $this->mailerLiteClassic()->client->deleteSubscriber($subscriberId);
        $this->assertTrue($isDeleted);
    }

}
