<?php

namespace App\Tests\Integration\Form\Type;

use App\Form\OrderProductType;
use App\Model\OrderProduct;
use Symfony\Component\Form\Test\TypeTestCase;

class OrderProductTypeTest extends TypeTestCase
{
    public function testProfileFormTypeSubmit(): void
    {
        $formData = [
            'name' => 'Product Name',
            'location' => 'Test Address',
            'phone' => '+380000000000',
            'email' => 'test@test.com',
            'description' => 'Product Description',
        ];

        $model = new OrderProduct();
        $form = $this->factory->create(OrderProductType::class, $model);

        $expected = new OrderProduct();
        $expected->name = 'Product Name';
        $expected->description = 'Product Description';
        $expected->phone = '+380000000000';
        $expected->email = 'test@test.com';
        $expected->location = 'Test Address';

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $form->getData());
    }
}