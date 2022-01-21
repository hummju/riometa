<?php

namespace riobasel\riometa;

use riobasel\riometa\services\MetaService;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;


class RioMeta extends Plugin
{

    public static $plugin;
    public $hasCpSettings = false;
    public $hasCpSection = true;
    public $schemaVersion = '0.1.0';

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
          'metaservice' => MetaService::class,
        ]);

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {

                $event->rules['POST riometa/<siteHandle>'] =        'riometa/edit/save';
                $event->rules['riometa/<siteHandle>'] =             'riometa/edit';

                $event->rules['POST riometa'] =                     'riometa/edit/save';
                $event->rules['riometa'] =                          'riometa/edit';

            }
        );


        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['meta.json'] =                      'riometa/public';
            }
        );

        Craft::info(
            Craft::t(
                'riometa',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

}
