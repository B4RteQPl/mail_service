<?php

namespace Tests\Feature\Service\SubscriberService\Traits;

use App\Interfaces\SubscriberService\MailProvider\MailProviderInterface;
use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\MailProviders\ActiveCampaign\ActiveCampaignMailProvider;
use App\Service\SubscriberService\MailProviders\ConvertKit\ConvertKitMailProvider;
use App\Service\SubscriberService\MailProviders\GetResponse\GetResponseMailProvider;
use App\Service\SubscriberService\MailProviders\MailerLite\MailerLiteMailProvider;
use App\Service\SubscriberService\MailProviders\MailerLiteClassic\MailerLiteClassicMailProvider;
use App\Service\SubscriberService\MailProviders\Sendgrid\SendgridMailProvider;
use App\Service\SubscriberService\Subscriber\SubscriberDraft;
use App\Service\SubscriberService\Subscriber\SubscriberVerified;
use App\Service\SubscriberService\SubscriberService;

trait MailingListProviderTrait
{

    protected SubscriberService $subscriberService;

    /**
     * @return array[]
     */
    public function validMailProviders(): array
    {
        return [
//            'ConvertKit' => [function () { return new ConvertKitMailProvider(env('TEST_CONVERTKIT_API_SECRET')); }],
//'Mailerlite' => [function () { return new MailerLiteMailProvider((env('TEST_MAILERLITE_API_KEY'))); }],
//'Mailerlite Classic' => [function () { return new MailerLiteClassicMailProvider(env('TEST_MAILERLITE_CLASSIC_API_KEY')); }],
//            'Active Campaign' => [function () { return new ActiveCampaignMailProvider(env('TEST_ACTIVECAMPAIGN_API_KEY'), env('TEST_ACTIVECAMPAIGN_API_URL')); }],
//            'GetResponse' => [function () { return new GetResponseMailProvider(env('TEST_GETRESPONSE_API_KEY')); }],
            'Sendgrid' => [function () { return new SendgridMailProvider(env('TEST_SENDGRID_API_KEY')); }],
        ];
    }

    /**=
     * @return array[]
     */
    public function invalidMailProviders(): array
    {
        return [
//            'ConvertKit' => [function () { return new ConvertKitMailProvider('invalid'); }],
//            'Mailerlite' => [function () { return new MailerLiteProvider('invalid'); }],
//'Mailerlite Classic' => [function () { return new MailerLiteClassicMailProvider('invalid'); }],
//            'Active Campaign' => [function () { return new ActiveCampaignMailProvider('invalid', env('TEST_ACTIVECAMPAIGN_API_URL')); }],
//            'GetResponse' => [function () { return new GetResponseMailProvider('invalid'); }],
            'Sendgrid' => [function () { return new SendgridMailProvider('invalid'); }],
        ];
    }

    private function getMailingList(MailProviderInterface $mailProvider)
    {
        $id = $mailProvider->getTestingGroupId();
        return new MailingList($id, 'Test Group', $mailProvider->getMailProviderType());
    }

    private function getNextMailingList(MailProviderInterface $mailProvider)
    {
        $id = $mailProvider->getTestingSecondGroupId();
        return new MailingList($id, 'Next Test Group', $mailProvider->getMailProviderType());
    }

    private function getFakeMailingList(MailProviderInterface $mailProvider)
    {
        return new MailingList('fake', 'Test Group', $mailProvider->getMailProviderType());
    }

    private function addTestSubscriberToList(SubscriberDraft|SubscriberVerified $subscriber, $subscriberService, ?MailingList $mailingList = null): SubscriberVerified
    {
        if ($subscriberService->getMailProvider() instanceof ConvertKitMailProvider) {
            $mailingList = $mailingList ?? $this->getMailingList($subscriberService->getMailProvider());

            return $this->subscriberService->addSubscriberToMailingList($subscriber, $mailingList);
        } else {
            return $this->subscriberService->addSubscriber($subscriber);
        }
    }
    //
    //    private function addTestSubscriberToMainList(SubscriberDraft|SubscriberVerified $subscriber, ?MailingList $mailingList = null, $subscriberService): SubscriberVerified
    //    {
    //        if ($subscriberService->getMailProvider() instanceof ConvertKitMailProvider) {
    //            $mailingList = $mailingList ?? $this->getMailingList($subscriberService->getMailProvider());
    //
    //            return $this->subscriberService->addSubscriberToMailingList($subscriber, $mailingList);
    //        } else {
    //            return $this->subscriberService->addSubscriber($subscriber);
    //        }
    //    }
    //
    //    private function addTestSubscriberToNextList(SubscriberDraft|SubscriberVerified $subscriber, $subscriberService, ?MailingList $mailingList = null): SubscriberVerified
    //    {
    //        if ($subscriberService->getMailProvider() instanceof ConvertKitMailProvider) {
    //            $nextMailingList = $mailingList ?? $this->getNextMailingList($subscriberService->getMailProvider());
    //
    //            return $this->subscriberService->addSubscriberToMailingList($subscriber, $nextMailingList);
    //        } else {
    //            return $this->subscriberService->addSubscriber($subscriber);
    //        }
    //    }
}
