<?php

namespace gumlet\imagetransformer;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\services\ImageTransforms;
use craft\web\twig\variables\CraftVariable;
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
     * @var \craft\base\imagetransforms\ImageTransformerInterface|null
     * Stores the original Craft transformer (currently not used, but kept for potential future use)
     */
    private ?\craft\base\imagetransforms\ImageTransformerInterface $originalTransformer = null;

    /**
     * @inheritdoc
     */
    public static function config(): array
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

        // Only initialize transformer if Craft application is fully bootstrapped
        // and the ImageTransforms service is available
        // This prevents errors during plugin installation
        if (!Craft::$app) {
            return;
        }

        // Override Craft's default image transformer with Gumlet transformer
        // This replaces the default transformer completely, so all image transforms
        // will go through Gumlet instead of Craft's native image processing
        try {
            if (Craft::$app->has('imageTransforms', true)) {
                $imageTransforms = Craft::$app->getImageTransforms();
                if ($imageTransforms) {
                    // Store the original transformer in case we need to restore it
                    $this->originalTransformer = $imageTransforms->getTransformer();
                    
                    // Set Gumlet transformer as the new default
                    // This means ALL image transforms will use Gumlet URLs with query parameters
                    // instead of Craft's path-based transform URLs (e.g., _300x300_crop_center-center_none/)
                    $imageTransforms->setTransformer(new GumletTransformer());
                }
            }
        } catch (\Throwable $e) {
            // Silently fail if service isn't available (e.g., during early installation phase)
        }

        // Register Gumlet service as a Twig variable (only for web requests)
        $plugin = $this;
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) use ($plugin) {
                try {
                    /** @var CraftVariable $variable */
                    $variable = $event->sender;
                    // Access the component via the plugin instance
                    if ($plugin && property_exists($plugin, 'gumlet') && $plugin->gumlet) {
                        $variable->set('gumlet', $plugin->gumlet);
                    }
                } catch (\Throwable $e) {
                    // Silently fail if service isn't available
                }
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

