<?php

namespace gumlet\imagetransformer\models;

use craft\base\Model;

/**
 * Gumlet settings model
 *
 * @property string|null $gumletDomain
 * @property bool $enabled
 * @property int $defaultQuality
 * @property string $defaultFormat
 * @property bool $autoFormat
 */
class Settings extends Model
{
    public ?string $gumletDomain = null;
    public bool $enabled = true;
    public int $defaultQuality = 80;
    public string $defaultFormat = 'auto';
    public bool $autoFormat = true;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['gumletDomain'], 'string'],
            [['enabled', 'autoFormat'], 'boolean'],
            [['defaultQuality'], 'integer', 'min' => 1, 'max' => 100],
            [
                ['defaultFormat'],
                'in',
                'range' => ['auto', 'webp', 'avif', 'jpg', 'png', 'gif', 'svg', 'ico', 'pdf'],
                'skipOnEmpty' => true,
            ],
            // Note: gumletDomain is not required here as it can be set via config file
        ];
    }
}

