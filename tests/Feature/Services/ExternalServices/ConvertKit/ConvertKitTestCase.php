<?php

namespace Tests\Feature\Services\ExternalServices\ConvertKit;

use App\ValueObjects\Email;
use Tests\Feature\Services\ExternalServices\ExternalServicesTestCase;

abstract class ConvertKitTestCase extends ExternalServicesTestCase
{
    public function convertKit()
    {
        return $this->externalServices()->convertKit->setClient(env('TEST_CONVERTKIT_API_SECRET'));
    }

    protected function assertSubscriberHasGroup(Email $email, int $groupId)
    {
        $subscriber = $this->mailerLite()->client->fetchSubscriber($email, $groupId);

        $this->assertIsArray($subscriber);
        $this->assertArrayHasKey('groups', $subscriber);
        $this->assertCount(1, $subscriber['groups']);
        $this->assertEquals($groupId, $subscriber['groups'][0]['id']);
    }

    protected function deleteSubscriber(string $subscriberId)
    {
        $isDeleted = $this->mailerLite()->client->deleteSubscriber($subscriberId);
        $this->assertTrue($isDeleted);
    }

}
