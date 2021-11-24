<?php

namespace Codein\IbexaSeoToolkit\Event;

use Symfony\Component\Form\FormBuilderInterface;

class MetaFieldFormEvent
{

    /** @var FormBuilderInterface */
    private $formBuilder;

    /** @var array */
    private $config;

    /**
     * @param FormBuilderInterface $formBuilder
     * @param array $config
     */
    public function __construct(FormBuilderInterface $formBuilder, array $config)
    {
        $this->formBuilder = $formBuilder;
        $this->config = $config;
    }

    /**
     * @return FormBuilderInterface
     */
    public function getFormBuilder(): FormBuilderInterface
    {
        return $this->formBuilder;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

}
