<?php

namespace App\Service;

use App\Form\FastRequestType;
use App\Form\OrderProductType;
use App\Model\FastRequest;
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

    public function createFastForm(): FormView
    {
        $this->form = $this->formFactory->create(FastRequestType::class, new FastRequest());
        return $this->form->createView();
    }
}
