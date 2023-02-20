<?php

namespace App\Services\ExternalServices\CircleSo\Client;

use App\Clients\BaseClient;
use App\Exceptions\Services\ExternalServices\ExternalServiceClientException;
use App\Services\ExternalServices\CircleSo\Data\CircleSoDataCommunity;
use App\Services\ExternalServices\CircleSo\Data\CircleSoDataInviteMember;
use App\Services\ExternalServices\CircleSo\Data\CircleSoDataSearchMember;
use App\Services\ExternalServices\CircleSo\Data\CircleSoDataSpace;
use App\Services\ExternalServices\CircleSo\Data\CircleSoDataSpaceGroup;
use App\Services\ExternalServices\CircleSo\Data\CircleSoDataSpaceGroupMember;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class CircleSoClient extends BaseClient implements CircleSoClientInterface
{
    protected string $authKey;
    protected string $endpoint = 'https://app.circle.so/api/v1';

    public function __construct(string $authKey)
    {
        $this->authKey = $authKey;
    }

    /**
     * @url https://api.circle.so/#ca5ece44-9ae1-4038-b995-8a9b79f0a6b6
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function isConnectionOk(): ?bool
    {
        try {
            $url = $this->endpoint . '/communities';

            $response = $this->request()->get($url);

            if ($response->successful()) {
                if($response->json('status') === 'unauthorized') {
                    return false;
                }

                if (!empty($response->json()[0]['id'])) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', 'Authorization failed', $e->getCode(), $e);
        }
    }


    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getCommunityList()
    {
        try {
            $url = $this->endpoint . '/communities';

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('CircleSo', 'Something wrong', $response->status());
            }

            return array_map(function($item) {
                $data = new CircleSoDataCommunity($item);
                return $data->toArray();
            }, $response->json());

        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function searchMember(string $communityId, Email $email)
    {
        try {
            $url = $this->endpoint . '/community_members/search?community_id='.$communityId .'&email='.$email->get();

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('CircleSo', 'Something wrong', $response->status());
            }

            $data = new CircleSoDataSearchMember($response->json());
            return $data->toArray();

        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getSpaceGroups(string $communityId)
    {
        try {
            $url = $this->endpoint . '/space_groups?community_id='. $communityId;

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('CircleSo', 'Something wrong', $response->status());
            }

            return array_map(function($item) {
                $data = new CircleSoDataSpaceGroup($item);
                return $data->toArray();
            }, $response->json());
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getMemberFromSpaceGroup(string $communityId, Email $email, string $spaceGroupId)
    {
        try {
            $url = $this->endpoint . '/space_group_member?community_id='.$communityId .'&email='.$email->get().'&space_group_id='.$spaceGroupId;
            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('CircleSo', 'Something wrong', $response->status());

            }
            $data = new CircleSoDataSpaceGroupMember($response->json());
            return $data->toArray();
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function inviteMember(string $communityId, Email $email, FirstName $firstName, LastName $lastName, array $spaceIds, array $spaceGroupIds)
    {
        try {
            $url = $this->endpoint . '/community_members?community_id='.$communityId . '&email='.$email->get(). '&name'.$firstName->get(). ' ' . $lastName->get(). '&space_group_ids='. json_encode($spaceGroupIds). '&space_ids='.json_encode($spaceIds);

            $response = $this->request()->post($url);

            if($response->json('success') === true) {
                $member = new CircleSoDataInviteMember($response->json('user'));
                return $member->toArray();
            }

            throw new ExternalServiceClientException('CircleSo', 'Something wrong', $response->status());
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function removeMemberFromCommunity(string $communityId, Email $email)
    {
        try {
            $url = $this->endpoint . '/community_members?community_id='.$communityId . '&email='.$email->get();

            $response = $this->request()->delete($url);

            if($response['success'] === true) {
                return true;
            }

            throw new ExternalServiceClientException('CircleSo', 'Something wrong', $response->status());
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getMemberFromSpace(string $communityId, Email $email, string $spaceId)
    {
        try {
            $url = $this->endpoint . '/space_members?community_id='.$communityId .'&email='.$email->get().'&space_id='.$spaceId;
            $response = $this->request()->get($url);

            if($response->json('success') === false) {
                return false;
            }

            if($response->json('success') === true) {
                return true;
            }
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function addMemberToSpace(string $communityId, Email $email, string $spaceId)
    {
        try {
            $url = $this->endpoint . '/space_members?community_id='.$communityId .'&email='.$email->get().'&space_id='.$spaceId;

            $response = $this->request()->post($url);

            if($response->json('success') === false) {
                return false;
            }

            if($response->json('success') === true) {
                return true;
            }
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function getSpaces(string $communityId)
    {
        try {
            $url = $this->endpoint . '/spaces?community_id='.$communityId;

            $response = $this->request()->get($url);

            if (!$response->successful()) {
                throw new ExternalServiceClientException('CircleSo', 'Something wrong', $response->status());
            }

            return array_map(function($item) {
                $data = new CircleSoDataSpace($item);
                return $data->toArray();
            }, $response->json());
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function removeMemberFromSpace(string $communityId, Email $email, string $spaceId)
    {
        try {
            $url = $this->endpoint . '/space_members?community_id='.$communityId .'&email='.$email->get().'&space_id='.$spaceId;

            $response = $this->request()->delete($url);

            if($response->json('success') === false) {
                return false;
            }

            if($response->json('success') === true) {
                return true;
            }
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function addMemberToSpaceGroup(string $communityId, Email $email, string $spaceGroupId)
    {
        try {
            $url = $this->endpoint . '/space_group_members?community_id='.$communityId .'&email='.$email->get().'&space_group_id='.$spaceGroupId;

            $response = $this->request()->post($url);

            if($response->json('success') === false) {
                return false;
            }

            if($response->json('success') === true) {
                return true;
            }
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws \App\Exceptions\Services\ExternalServices\ExternalServiceClientException
     */
    public function removeMemberFromSpaceGroup(string $communityId, Email $email, string $spaceGroupId)
    {
        try {
            $url = $this->endpoint . '/space_group_members?community_id='.$communityId .'&email='.$email->get().'&space_group_id='.$spaceGroupId;

            $response = $this->request()->delete($url);

            if($response['success'] === false) {
                return false;
            }

            if($response['success'] === true) {
                return true;
            }
        } catch (\Exception $e) {
            throw new ExternalServiceClientException('CircleSo', $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @url https://api.slack.com/web#slack-web-api__basics__post-bodies__url-encoded-bodies
     */
    private function request(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => 'Token ' . $this->authKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}
