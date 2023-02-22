<?php

namespace Tests\Feature\Services\SubscriberService\Mocks;

use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListInterface;
use App\Interfaces\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListManagerInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberManagerInterface;
use App\Interfaces\Services\SubscriberManager\SubscriberServices\ServiceInterface;
use App\Services\SubscriberManager\Subscriber\SubscriberList\SubscriberListManager;
use App\ValueObjects\Email;
use App\ValueObjects\FirstName;
use App\ValueObjects\LastName;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class MockProvider extends TestCase
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getServiceMock(): ServiceInterface|MockObject
    {
        $service = $this->createMock(ServiceInterface::class);

        $service->method('isConnectionOk')->willReturn(true);
        $service->method('getSubscriberList')->willReturn([$this->getSubscriberListMock()]);
        $service->method('addSubscriberToSubscriberList')->willReturn($this->getSubscriberMock());
        $service->method('deleteSubscriberFromSubscriberList')->willReturn($this->getSubscriberMock());

        return $service;
    }

    public function getInvalidServiceMock(): ServiceInterface|MockObject
    {
        $service = $this->createMock(ServiceInterface::class);

        $service->method('isConnectionOk')->willReturn(false);
        $service->method('getSubscriberList')->willReturn([$this->getSubscriberListMock()]);
        $service->method('addSubscriberToSubscriberList')->willReturn($this->getSubscriberMock());
        $service->method('deleteSubscriberFromSubscriberList')->willReturn($this->getSubscriberMock());

        return $service;
    }

    public function getSubscriberManagerMock(): SubscriberManagerInterface|MockObject
    {
        $subscriberManager = $this->createMock(SubscriberManagerInterface::class);

        $subscriberManager->method('isConnectionOk')->willReturn(true);
        $subscriberManager->method('getSubscriberList')->willReturn([$this->getSubscriberListMock()]);
        $subscriberManager->method('addSubscriberToSubscriberList')->willReturn($this->getSubscriberMock());
        $subscriberManager->method('deleteSubscriberFromSubscriberList')->willReturn($this->getSubscriberMock());

        return $subscriberManager;
    }

    public function getSubscriberListManagerMock(): SubscriberListManagerInterface|MockObject
    {
        $subscriberListManager = $this->createMock(SubscriberListManagerInterface::class);

        $subscriberListManager->method('getSubscriberList')->willReturn([$this->getSubscriberListMock()]);
        $subscriberListManager->method('getList')->willReturn($this->getSubscriberListMock());
        $subscriberListManager->method('addList')->willReturn($this->getSubscriberListMock());
        $subscriberListManager->method('deleteList')->willReturn($this->getSubscriberListMock());


        return $subscriberListManager;
    }

    public function getSubscriberMock(): SubscriberInterface|MockObject
    {
        $subscriber = $this->createMock(SubscriberInterface::class);

//        $subscriber->method('getId')->willReturn('test');
        $subscriber->method('email')->willReturn(new Email('example@email.com'));
        $subscriber->method('firstName')->willReturn(new FirstName('John'));
        $subscriber->method('lastName')->willReturn(new LastName('Show'));
        $subscriber->method('mailingLists')->willReturn(new SubscriberListManager());
        $subscriber->method('channelLists')->willReturn(new SubscriberListManager());

        return $subscriber;
    }

    public function getSubscriberListMock(): SubscriberListInterface|MockObject
    {
        $list = $this->createMock(SubscriberListInterface::class);

        $list->method('getId')->willReturn('test');
        $list->method('getName')->willReturn('test');
        $list->method('getType')->willReturn('test');
        $list->method('hasType')->willReturn(true);
        $list->method('toArray')->willReturn(['test']);

        return $list;
    }
}
