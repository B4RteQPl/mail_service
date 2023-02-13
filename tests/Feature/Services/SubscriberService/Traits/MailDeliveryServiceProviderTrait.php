<?php

namespace Tests\Feature\Services\SubscriberService\Traits;

use App\Services\SubscriberManager\SubscriberServices\MailingServices\ActiveCampaign\DeliveryService as ActiveCampaignRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\ConvertKit\DeliveryService as ConvertKitRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\GetResponse\DeliveryService as GetResponseRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\MailerLite\DeliveryService as MailerLiteRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\MailerLiteClassic\DeliveryService as MailerLiteClassicRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\Sendgrid\DeliveryService as SendgridRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingServices\Mailchimp\DeliveryService as MailchimpRequester;
use App\Services\SubscriberManager\SubscriberServices\MailingService;

trait MailDeliveryServiceProviderTrait
{

    protected MailingService $subscriberService;

    /**
     * @return array[]
     */
    public function validMailRequester(): array
    {
        return [
            'Mailerlite' => [function () { return new MailerLiteRequester((env('TEST_MAILERLITE_API_KEY'))); }],
//            'ConvertKit' => [function () { return new ConvertKitRequester(env('TEST_CONVERTKIT_API_SECRET')); }],
            'Active Campaign' => [function () { return new ActiveCampaignRequester(env('TEST_ACTIVECAMPAIGN_API_KEY'), env('TEST_ACTIVECAMPAIGN_API_URL')); }],
//            'Mailerlite Classic' => [function () { return new MailerLiteClassicRequester(env('TEST_MAILERLITE_CLASSIC_API_KEY')); }],
            'GetResponse' => [function () { return new GetResponseRequester(env('TEST_GETRESPONSE_API_KEY')); }],
            'Sendgrid' => [function () { return new SendgridRequester(env('TEST_SENDGRID_API_KEY')); }],
            'Mailchimp' => [function () { return new MailchimpRequester(env('TEST_MAILCHIMP_API_KEY')); }],
        ];
    }

    /**=
     * @return array[]
     */
    public function invalidMailRequester(): array
    {
        return [
            'Mailerlite' => [function () { return new MailerLiteRequester('invalid'); }],
            'ConvertKit' => [function () { return new ConvertKitRequester('invalid'); }],
            'Active Campaign' => [function () { return new ActiveCampaignRequester('invalid', env('TEST_ACTIVECAMPAIGN_API_URL')); }],
            'Mailerlite Classic' => [function () { return new MailerLiteClassicRequester('invalid'); }],
            'GetResponse' => [function () { return new GetResponseRequester('invalid'); }],
            'Sendgrid' => [function () { return new SendgridRequester('invalid'); }],
            'Mailchimp' => [function () { return new MailchimpRequester('invalid'); }],
        ];
    }

    //    private function getMailingList(MailRequesterInterface $mailProvider)
    //    {
    //        $id = $mailProvider->getTestingGroupId();
    //        return new MailingList($id, 'Test Group', $mailProvider->getType());
    //    }
    //
    //    private function getNextMailingList(MailRequesterInterface $mailProvider)
    //    {
    //        $id = $mailProvider->getTestingSecondGroupId();
    //        return new MailingList($id, 'Next Test Group', $mailProvider->getType());
    //    }
    //
    //    private function getFakeMailingList(MailRequesterInterface $mailProvider)
    //    {
    //        return new MailingList('fake', 'Test Group', $mailProvider->getType());
    //    }
    //    private function addTestSubscriberToList(SubscriberInterface $subscriber, $subscriberService, ?SubscriberListInterface $mailingList = null): SubscriberVerified
    //    {
    //        if ($subscriberService->getMailProvider() instanceof ConvertKitRequester) {
    //            $mailingList = $mailingList ?? $this->getMailingList($subscriberService->getMailProvider());
    //
    //            return $this->subscriberService->addSubscriberToMailingList($subscriber, $mailingList);
    //        } else {
    //            return $this->subscriberService->addSubscriber($subscriber);
    //        }
    //    }
    //
    //    private function addTestSubscriberToMainList(SubscriberDraft|SubscriberVerified $subscriber, ?MailingList $mailingList = null, $subscriberService): SubscriberVerified
    //    {
    //        if ($subscriberService->getMailProvider() instanceof ConvertKitRequester) {
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
    //        if ($subscriberService->getMailProvider() instanceof ConvertKitRequester) {
    //            $nextMailingList = $mailingList ?? $this->getNextMailingList($subscriberService->getMailProvider());
    //
    //            return $this->subscriberService->addSubscriberToMailingList($subscriber, $nextMailingList);
    //        } else {
    //            return $this->subscriberService->addSubscriber($subscriber);
    //        }
    //    }
}
