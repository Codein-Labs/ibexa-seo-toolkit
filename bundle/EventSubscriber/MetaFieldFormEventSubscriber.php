<?php

namespace Codein\IbexaSeoToolkit\EventSubscriber;

use Codein\IbexaSeoToolkit\DataTransformer\StringToBooleanTransformer;
use Codein\IbexaSeoToolkit\Event\MetaFieldFormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MetaFieldFormEventSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            MetaFieldFormEvent::class => [
                ['addToForm', 0],
            ],
        ];
    }

    public function addToForm(MetaFieldFormEvent $event) {
        $formBuilder = $event->getFormBuilder();
        $config = $event->getConfig();

        switch ($config['type']) {
            case 'ezboolean':
                $formBuilder->add($config['key'], CheckboxType::class, [
                    'label' => $config['label'],
                    'attr' => ['type' => 'boolean']
                ])->addModelTransformer(new StringToBooleanTransformer($config['key']));
                break;
            case 'ezstring':
                $formBuilder->add($config['key'], TextType::class, [
                    'label' => $config['label'],
                    'attr' => ['type' => 'string']
                ]);
                break;
            default:
        }

    }
}
