<?php

namespace Tests\Feature\Service\SubscriberService\Unit\MailingList;

use App\Service\SubscriberService\MailingList\MailingList;
use App\Service\SubscriberService\Subscriber;
use PHPUnit\Framework\TestCase;

class MailingListTest extends TestCase
{
    /**
     * @test
     */
    public function when_mailing_list_is_created_then_all_properties_can_be_set_using_constructor ()
    {
        // given
        $id = 'id';
        $name = 'name';
        $mailProviderType = 'type';

        // when
        $mailingList = new MailingList($id, $name, $mailProviderType);

        // then
        $this->assertEquals($id, $mailingList->getId());
        $this->assertEquals($name, $mailingList->getName());
        $this->assertEquals($mailProviderType, $mailingList->getMailProviderType());
    }

    /**
     * @test
     */
    public function when_mailing_list_is_created_then_all_properties_can_be_changed ()
    {
        // given
        $mailingList = new MailingList('id', 'name', 'type');

        // when
        $newId = '111';
        $newName = 'new name';
        $newType = 'new type';
        $mailingList->setId($newId);
        $mailingList->setName($newName);
        $mailingList->setMailProviderType($newType);

        // then
        $this->assertEquals($newId, $mailingList->getId());
        $this->assertEquals($newName, $mailingList->getName());
        $this->assertEquals($newType, $mailingList->getMailProviderType());
    }

    /**
     * @test
     */
    public function when_mailing_list_id_is_empty_then_throw_exception ()
    {
        // expect
        $this->expectException(\InvalidArgumentException::class);

        // when
        new MailingList('', 'name', 'type');
    }

    /**
     * @test
     */
    public function when_mailing_list_name_is_empty_then_throw_exception ()
    {
        // expect
        $this->expectException(\InvalidArgumentException::class);

        // when
        new MailingList('id', '', 'type');
    }

    /**
     * @test
     */
    public function when_mailing_list_type_is_empty_then_throw_exception ()
    {
        // expect
        $this->expectException(\InvalidArgumentException::class);

        // when
        new MailingList('id', 'name', '');
    }

    /**
     * @test
     */
    public function when_mailing_list_is_valid_then_is_invalid_returns_false ()
    {
        // given
        $mailingList = new MailingList('123', 'name', 'type');

        // when
        $result = MailingList::isInvalid($mailingList);

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function when_mailing_list_is_valid_then_is_invalid_array_returns_false ()
    {
        // given
        $mailingList = new MailingList('123', 'name', 'type');

        // when
        $result = MailingList::isInvalidArray([$mailingList]);

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function when_mailing_list_array_is_invalid_then_is_invalid_array_returns_true ()
    {
        // when
        $result = MailingList::isInvalidArray([new Subscriber('exmple@email.com')]);

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function when_mailing_list_array_is_empty_then_is_invalid_array_returns_false ()
    {
        // when
        $result = MailingList::isInvalidArray([]);

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function when_mail_provider_type_equal_then_has_type_returns_true ()
    {
        // when
        $mailProviderType = 'Example';
        $mailingList = new MailingList('id', 'name', $mailProviderType);

        // then
        $this->assertTrue($mailingList->hasMailProviderType($mailProviderType));
    }

    /**
     * @test
     */
    public function when_mail_provider_type_is_not_equal_then_hasType_returns_false ()
    {
        // when
        $mailProviderType = 'Example';
        $mailingList = new MailingList('id', 'name', $mailProviderType);

        // then
        $this->assertFalse($mailingList->hasMailProviderType('Other type'));
    }
}
