<?php

namespace gumlet\imagetransformer;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Assets;
use gumlet\imagetransformer\models\Settings;
use gumlet\imagetransformer\services\Gumlet as GumletService;
use gumlet\imagetransformer\transformers\GumletTransformer;
use yii\base\Event;

/**
 * Gumlet plugin
 *
 * Adds Gumlet powered asset transforms to Craft CMS.
 *
 * @method static Plugin getInstance()
 * @method GumletService getGumlet()
 * @property GumletService $gumlet
 */
class Plugin extends BasePlugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = false;

    /**
     * @inheritdoc
     */
    protected static function config(): array
    {
        return [
            'components' => [
                'gumlet' => GumletService::class,
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        // Register the Gumlet transformer
        Event::on(
            Assets::class,
            Assets::EVENT_REGISTER_IMAGE_TRANSFORMERS,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = GumletTransformer::class;
            }
        );
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ?Settings
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): ?string
    {
        return null; // Settings are managed via config file
    }
}

