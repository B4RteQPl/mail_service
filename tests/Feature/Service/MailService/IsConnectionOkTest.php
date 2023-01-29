<?php

namespace Tests\Feature\Service\MailService;

use App\Service\MailService\MailService;
use Tests\Feature\Service\MailService\Traits\MailProvidersTrait;
use Tests\TestCase;

class IsConnectionOkTest extends TestCase
{

    use MailProvidersTrait;

    /**
     * @test
     * @dataProvider mailProviders
     */
    public function when_api_key_is_valid_then_isConnectionOk_should_return_true($mailProvider)
    {
        // given
        $mailService = new MailService($mailProvider());

        // when
        $isConnectionOk = $mailService->isConnectionOk();

        // then
        $this->assertTrue($isConnectionOk);
    }

    /**
     * @test
     * @dataProvider invalidMailProviders
     */
    public function when_api_key_is_invalid_then_isConnectionOk_should_return_false($invalidMailProvider)
    {
        // given
        $invalidMailService = new MailService($invalidMailProvider());

        // when
        $isConnectionOk = $invalidMailService->isConnectionOk();

        // then
        $this->assertFalse($isConnectionOk);
    }

}
