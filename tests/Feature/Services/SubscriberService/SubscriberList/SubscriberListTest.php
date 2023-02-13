<?php

namespace Tests\Feature\Services\SubscriberService\SubscriberList;

use App\Services\SubscriberManager\Subscriber\SubscriberList\types\BaseList;
use Tests\Feature\Services\SubscriberService\Traits\ListProviderTrait;
use Tests\TestCase;

class SubscriberListTest extends TestCase
{

    use ListProviderTrait;

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_list_is_created_then_all_properties_can_be_set_using_constructor ($listClass)
    {
        // given
        $id = 'id';
        $name = 'name';
        $type = 'type';

        // when
        $list = new $listClass($id, $name, $type);

        // then
        $this->assertEquals($id, $list->id);
        $this->assertEquals($name, $list->name);
        $this->assertEquals($type, $list->type);
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_mailing_list_is_created_then_all_properties_can_be_changed ($listClass)
    {
        // given
        $list = new $listClass('id', 'name', 'type');

        // when
        $newId = '111';
        $newName = 'new name';
        $newType = 'new type';
        $list->setId($newId);
        $list->setName($newName);
        $list->setType($newType);

        // then
        $this->assertEquals($newId, $list->id);
        $this->assertEquals($newName, $list->name);
        $this->assertEquals($newType, $list->type);
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_mailing_list_id_is_empty_then_throw_exception ($listClass)
    {
        // expect
        $this->expectException(\InvalidArgumentException::class);

        // when
        new $listClass('', 'name', 'type');
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_mailing_list_name_is_empty_then_throw_exception ($listClass)
    {
        // expect
        $this->expectException(\InvalidArgumentException::class);

        // when
        new $listClass('id', '', 'type');
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_mailing_list_type_is_empty_then_throw_exception ($listClass)
    {
        // expect
        $this->expectException(\InvalidArgumentException::class);

        // when
        new $listClass('id', 'name', '');
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_mailing_list_is_valid_then_is_invalid_returns_false ($listClass)
    {
        // given
        $list = new $listClass('123', 'name', 'type');

        // when
        $result = $listClass::isInvalid($list);

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_mailing_list_is_valid_then_is_invalid_array_returns_false ($listClass)
    {
        // given
        $list = new $listClass('123', 'name', 'type');

        // when
        $result = $listClass::isInvalidArray([$list]);

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_mailing_list_array_is_invalid_then_is_invalid_array_returns_true ($listClass)
    {
        // when
        $result = $listClass::isInvalidArray(['invalid']);

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_mailing_list_array_is_empty_then_is_invalid_array_returns_false ($listClass)
    {
        // when
        $result = $listClass::isInvalidArray([]);

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_mail_provider_type_equal_then_has_type_returns_true ($listClass)
    {
        // when
        $type = 'Example';
        $list = new $listClass('id', 'name', $type);

        // then
        $this->assertTrue($list->hasType($type));
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_mail_provider_type_is_not_equal_then_hasType_returns_false ($listClass)
    {
        // when
        $type = 'Example';
        $list = new $listClass('id', 'name', $type);

        // then
        $this->assertFalse($list->hasType('Other type'));
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_list_is_created_then_status_is_not_verified_returns_true_as_a_default ($listClass)
    {
        // when
        $list = new $listClass('id', 'name', 'type');

        // then
        $this->assertTrue($list->isStatusNotVerified());
        $this->assertFalse($list->isStatusVerificationPending());
        $this->assertFalse($list->isStatusVerified());
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_status_is_set_to_verified_then_status_is_verified_returns_true ($listClass)
    {
        // when
        $list = new $listClass('id', 'name', 'type');
        $list->setStatusVerified();

        // then
        $this->assertTrue($list->isStatusVerified());
        $this->assertFalse($list->isStatusNotVerified());
        $this->assertFalse($list->isStatusVerificationPending());
    }


    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_status_is_set_to_verification_pending_then_status_is_verification_pending_returns_true($listClass)
    {
        // when
        $list = new $listClass('id', 'name', 'type');
        $list->setStatusVerificationPending();

        // then
        $this->assertTrue($list->isStatusVerificationPending());
        $this->assertFalse($list->isStatusNotVerified());
        $this->assertFalse($list->isStatusVerified());
    }

    /**
     * @test
     * @dataProvider ListClassProvider
     */
    public function when_to_array_is_returned_then_format_is_correct($listClass)
    {
        // given
        $id = 'id';
        $name = 'name';
        $type = 'type';

        $list = new $listClass($id, $name, $type);

        // when
        $result = $list->toArray();

        // then
        $this->assertEquals($id, $result['id']);
        $this->assertEquals($name, $result['name']);
        $this->assertEquals($type, $result['type']);
        $this->assertEquals(BaseList::STATUS_NOT_VERIFIED, $result['status']);
    }
}
