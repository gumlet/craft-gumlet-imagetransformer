<?php

return [
    // Gumlet domain (e.g., 'your-domain.gumlet.io')
    'gumletDomain' => getenv('GUMLET_DOMAIN') ?: '',

    // Enable Gumlet transforms
    'enabled' => getenv('GUMLET_ENABLED') !== 'false',

    // Default quality for images (1-100)
    'defaultQuality' => 80,

    // Default format (auto, webp, avif, jpg, png, etc.)
    'defaultFormat' => 'auto',

    // Enable automatic format selection based on browser support
    'autoFormat' => true,
];

