<?php

return [
    // Gumlet domain (e.g., 'your-domain.gumlet.io' or 'https://your-domain.gumlet.io')
    // Protocol and trailing slashes will be automatically stripped
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

