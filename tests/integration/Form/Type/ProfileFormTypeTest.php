<?php

namespace App\Tests\Integration\Form\Type;

use App\Entity\Profile;
use App\Form\ProfileType;
use Symfony\Component\Form\Test\TypeTestCase;

class ProfileFormTypeTest extends TypeTestCase
{
    public function testProfileFormTypeSubmit(): void
    {
        $formData = [
            'name' => 'Profile Name',
            'locale' => 'uk',
            'timezone' => 'Europe/Kyiv',
        ];

        $model = new Profile();
        $form = $this->factory->create(ProfileType::class, $model);

        $expected = new Profile();
        $expected->setName('Profile Name');
        $expected->setLocale('uk');
        $expected->setTimezone('Europe/Kyiv');
        $expected->setActive(false);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $form->getData());
    }
}
