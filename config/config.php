<?php

declare(strict_types=1);

return [

    // Attributes Database Tables
    'tables' => [

        'attributes' => 'attributes',
        'attribute_entity' => 'attribute_entity',
        'attribute_boolean_values' => 'attribute_boolean_values',
        'attribute_datetime_values' => 'attribute_datetime_values',
        'attribute_integer_values' => 'attribute_integer_values',
		'attribute_decimal_values' => 'attribute_decimal_values',
        'attribute_text_values' => 'attribute_text_values',
        'attribute_varchar_values' => 'attribute_varchar_values',
		'attribute_custom_values' => 'attribute_custom_values',

    ],

    // Attributes Models
    'models' => [

        'attribute' => \Rinvex\Attributes\Models\Attribute::class,
        'attribute_entity' => \Rinvex\Attributes\Models\AttributeEntity::class,

    ],

];
