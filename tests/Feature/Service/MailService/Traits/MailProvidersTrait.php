<?php

namespace Tests\Feature\Service\MailService\Traits;

use App\Service\MailService\MailProviders\MailerLite\MailerLiteMailProvider;
use App\Service\MailService\MailProviders\MailerLiteClassic\MailerLiteClassicMailProvider;

trait MailProvidersTrait
{

    /**
     * @return array[]
     */
    public function mailProviders(): array
    {
        return [
            'Mailerlite' => [function () { return new MailerLiteMailProvider(env('TESTING_MAILERLITE_API_KEY')); }],
            'Mailerlite Classic' => [function () { return new MailerLiteClassicMailProvider(env('TESTING_MAILERLITE_CLASSIC_API_KEY')); }],
        ];
    }

    /**
     * @return array[]
     */
    public function invalidMailProviders(): array
    {
        return [
            'Mailerlite' => [function () { return new MailerLiteMailProvider('invalid_api_key'); }],
            'Mailerlite Classic' => [function () { return new MailerLiteClassicMailProvider('invalid_api_key'); }],
        ];
    }

}
