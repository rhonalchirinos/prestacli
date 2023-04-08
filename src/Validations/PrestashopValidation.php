<?php

namespace Presta\Validations;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;


class PrestashopValidation
{

    /**
     * @var string 
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $tab;

    /**
     * 
     */
    private $validator;

    /**
     * @var mixed
     */
    private $groups;

    /**
     * @var mixed
     */
    private $constraint;

    /**
     * @var mixed
     */
    private $violations;

    /**
     * @var Array
     */
    public static $typeStandard = [
        'administration',
        'advertising_marketing',
        'analytics_stats',
        'billing_invoicing',
        'checkout',
        'content_management',
        'emailing',
        'export',
        'front_office_features',
        'i18n_localization',
        'market_place',
        'merchandizing',
        'migration_tools',
        'mobile',
        'others',
        'payment_security',
        'payments_gateways',
        'pricing_promotion',
        'quick_bulk_update',
        'seo',
        'search_filter',
        'shipping_logistics',
        'slideshows',
        'smart_shopping',
        'social_networks'
    ];

    /**
     * 
     */
    function __construct($type, $tab, $name)
    {
        $this->name = $name;
        $this->type = $type;
        $this->tab  = $tab;

        $this->validator = Validation::createValidator();

        $this->groups = new Assert\GroupSequence(
            [
                'Default', 'custom'
            ]
        );
    }

    /**
     * 
     */
    private function createConstraint()
    {
        $constraint =  [
            'type' => [
                new Assert\NotBlank(),
                new Assert\Choice(
                    [
                        'choices' => [
                            'payment', 'shipping', 'standard', 'service'
                        ]
                    ]
                )
            ],
            'name' => new Assert\Length(
                [
                    'min' => 5
                ]
            )
        ];

        if ($this->type == 'standard' || $this->type == 'service') {
            $constraint['tab_module'] = [
                new Assert\NotBlank(),
                new Assert\Choice(
                    [
                        'choices' =>  self::$typeStandard,
                    ]
                )
            ];
            $input['tab_module'] = $this->tab;
        }

        $this->constraint = new Assert\Collection($constraint);
    }

    /**
     * 
     */
    public function validated()
    {
        $name = $this->name;
        $type = $this->type;
        $tab  = $this->tab;

        $input = compact('type', 'name');

        if ($this->type == 'standard' || $this->type == 'service') {
            $input['tab_module'] = $this->tab;
        }

        $this->createConstraint();

        $this->violations = $this->validator->validate($input, $this->constraint, $this->groups);

        return !$this->hasError();
    }

    /**
     * 
     */
    public function hasError()
    {
        return $this->violations->count() > 0;
    }

    /**
     * 
     */
    public function getMessage()
    {
        $message = '';
        foreach ($this->violations as $value) {
            $error = $value->getPropertyPath();
            $_message = $value->getMessage();
            $message = $message . "$error $_message\n";
        }

        return $message;
    }
}
