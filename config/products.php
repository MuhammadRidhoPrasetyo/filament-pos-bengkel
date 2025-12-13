<?php

return [
    // Default label parts to include when using the `$product->label` accessor.
    // Set to true to include that part by default.
    'default_label' => [
        'brand' => true,
        'sku'   => false,
        'type'  => false,
    ],

    // Separator used when joining label parts
    'separator' => ' | ',
];
