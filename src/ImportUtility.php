<?php

namespace wavedesign\crafthrcommencementimportutility;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\console\Application as ConsoleApplication;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;
use wavedesign\crafthrcommencementimportutility\services\RegistrarImporterCraft;
use wavedesign\crafthrcommencementimportutility\services\RegistrarImporterCraftService;

/**
 * HR Commencement Import Utility plugin
 *
 * @method static ImportUtility getInstance()
 * @property-read RegistrarImporterCraft $registrarImporterCraft
 * @property-read RegistrarImporterCraftService $registrarImporterCraftService
 */
class ImportUtility extends Plugin
{
    public string $schemaVersion = '1.0.0';

        /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public bool $hasCpSettings = true;

    /**
     * Set to `true` if the plugin should have its own section (main nav item) in the control panel.
     *
     * @var bool
     */
    public bool $hasCpSection = true;

    public static function config(): array
    {
        return [
            'components' => ['registrarImporterCraft' => RegistrarImporterCraft::class, 'registrarImporterCraftService' => RegistrarImporterCraftService::class],
        ];
    }

    protected function createSettingsModel(): ?craft\base\Model
    {
        return new \wavedesign\crafthrcommencementimportutility\models\Settings();
    }

    protected function settingsHtml(): ?string
    {
        return \Craft::$app->getView()->renderTemplate(
            '_hr-commencement-import-utility/settings',
            [ 'settings' => $this->getSettings() ]
        );
    }

    public function init()
    {
        parent::init();
        //self::$plugin = $this;

        $this->setComponents([
            'CraftService' => services\RegistrarImporterCraftService::class,
        ]);

               // Register our site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['siteActionTrigger1'] = '_hr-commencement-import-utility/default';
            }
        );

        // Defer most setup tasks until Craft is fully initialized
        Craft::$app->onInit(function() {
            $this->attachEventHandlers();
            // ...
        });
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/4.x/extend/events.html to get started)
    }
}
