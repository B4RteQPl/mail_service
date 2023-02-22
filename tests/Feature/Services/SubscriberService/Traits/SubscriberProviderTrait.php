<?php

namespace Tests\Feature\Services\SubscriberService\Traits;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Services\SubscriberManager\Subscriber\Subscriber;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;

trait SubscriberProviderTrait
{

    public function getSubscriberWithRequiredFields(): SubscriberInterface
    {
        return new Subscriber(new Email($this->getUniqueEmail()));
    }

    public function getSubscriberWithFirstNameAndLastName(): SubscriberInterface
    {
        return new Subscriber(new Email($this->getUniqueEmail()), new FirstName('John'), new LastName('Show'));
    }

    public function getUniqueEmail(): string
    {
        return uniqid('test-') . '@fakedomain.com';
    }
}
