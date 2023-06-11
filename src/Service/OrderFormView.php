<?php

namespace App\Service;

use App\Form\OrderProductType;
use App\Model\OrderProduct;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormView;

class OrderFormView
{
    private FormFactoryInterface $formFactory;

    private $form;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function createOrderForm(): FormView
    {
        $this->form = $this->formFactory->create(OrderProductType::class, new OrderProduct());
        return $this->form->createView();
    }
}
