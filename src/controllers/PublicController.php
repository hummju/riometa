<?php

namespace riobasel\riometa\controllers;

use riobasel\riometa\RioMeta;

use Craft;
use craft\web\Controller;
use craft\helpers\UrlHelper;






class PublicController extends Controller
{


    protected $allowAnonymous = true;


    public function actionIndex(){
        
        $currentSite = Craft::$app->getSites()->currentSite;

        $info = RioMeta::$plugin->metaservice->serializeMeta( $currentSite->id );
        if( !$info ){
            $info = [];
        }

        return $this->asJson(
            [

                'id'=>$currentSite->id,
                'groupId'=>$currentSite->groupId,
                'handle'=>$currentSite->handle,
                'language'=>$currentSite->language,
                'primary'=>$currentSite->primary,
                'enabled'=>$currentSite->enabled,
                'hasUrls'=>$currentSite->hasUrls,
                'name'=>$currentSite->name,
                'baseUrl'=>$currentSite->baseUrl,

                'info' => $info,

            ]
        );

    }

}
