<?php

namespace App\Services\SubscriberManager\SubscriberServices\ChannelServices\CircleSo;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\ChannelServices\ChannelDeliveryServiceInterface;
use App\Services\SubscriberManager\SubscriberServices\ChannelServices\BaseDeliveryService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class DeliveryService extends BaseDeliveryService implements ChannelDeliveryServiceInterface
{
    const TYPE = 'CIRCLE_SO';
    protected string $type = self::TYPE;
    protected string $endpoint = 'https://app.circle.so/api/v1';
    protected string $communityId = '1';

    public function isConnectionOk(): bool
    {
        $url = $this->endpoint . '/me';

        $response = $this->requestWithHeaders()->get($url);

        return !empty($response->json()['id']);
    }

    /**
     * @return SubscriberListInterface[]
     */
    public function getCommunityList(): array
    {
        $url = $this->endpoint . '/communities';

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->getCommunityList();
    }

    /**
     * @return SubscriberListInterface[]
     * @url https://api.circle.so/#c2faf0c4-9903-4cdb-84c5-6a587e0f6c40
     */
    public function getCommunitySpaceList(SubscriberListInterface $communityList): array
    {
        $url = $this->endpoint . '/spaces?community_id='. $communityList->id;

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->getCommunitySpaceList($communityList);
    }

    public function getSpacesGroups(): array
    {
        $url = $this->endpoint . '/space_groups?community_id='. $this->communityId;

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->getChannelList();
    }

    public function verifySubscriberSpace(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/space_members?email=' . $subscriber->email->get() . '&space_id=' . $subscriberList->id . '&community_id=' . $subscriberList->list->id;

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->updateSubscriber($subscriber, $subscriberList);
    }

    public function verifySubscriberSpaceGroup(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/space_group_members?email=' . $subscriber->email->get() . '&space_group_id=' . $subscriberList->id . '&community_id=' . $this->communityId;

        $response = $this->requestWithHeaders()->get($url);

        return Responder::for($response)->updateSubscriber($subscriber, $subscriberList);
    }

    public function addSubscriberToCommunitySpaceList(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        https://app.circle.so/api/v1/community_members
        //?email=member@community.com
        //&name=John Doe
        // &community_id={{community_id}}
        //&space_ids[]=1
        //&space_group_ids[]=2
        //&member_tag_ids[]=5
        //&skip_invitation=true
        //&location=New York

        $url = $this->endpoint . '/community_members?email=' . $subscriber->email->get() . '&space_id=' . $subscriberList->id . '&community_id=' . $this->communityId;

        $response = $this->requestWithHeaders()->post($url);

        return Responder::for($response)->updateSubscriberAfterAddToSubscriberList($subscriber, $subscriberList);
    }

    public function deleteSubscriberFromCommunity()
    {
        https://app.circle.so/api/v1/community_members?community_id={{community_id}}&email=different@user.com
    }

    public function deleteSubscriberFromCommunitySpace()
    {
        https://app.circle.so/api/v1/community_members?community_id={{community_id}}&email=different@user.com
    }

    public function deleteSubscriberFromCommunitySpaceGroup()
    {
        https://app.circle.so/api/v1/community_members?community_id={{community_id}}&email=different@user.com
    }



    public function deleteSubscriberFromSpace(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/space_members?email='.$subscriber->email->get().'&space_id=' .$subscriberList->id.'&community_id='.$this->communityId;

        $response = $this->requestWithHeaders()->delete($url);

        return Responder::for($response)->updateSubscriberAfterDeleteFromSubscriberList($subscriber, $subscriberList);
    }

    public function addSubscriberToSpaceGroup(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/space_group_members?email=' . $subscriber->email->get() . '&space_group_id=' . $subscriberList->id . '&community_id=' . $this->communityId;

        $response = $this->requestWithHeaders()->post($url);

        return Responder::for($response)->updateSubscriberAfterAddToSpaceGroup($subscriber, $subscriberList);
    }

    public function deleteSubscriberFromSpaceGroup(SubscriberInterface $subscriber, SubscriberListInterface $subscriberList): SubscriberInterface
    {
        $url = $this->endpoint . '/space_group_members?email='.$subscriber->email->get().'&space_group_id=' .$subscriberList->id.'&community_id='.$this->communityId;

        $response = $this->requestWithHeaders()->delete($url);

        return Responder::for($response)->updateSubscriberAfterDeleteFromSpaceGroup($subscriber, $subscriberList);
    }

    /**
     * @url https://api.slack.com/web#slack-web-api__basics__post-bodies__url-encoded-bodies
     */
    private function requestWithHeaders(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Token ' . $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}

