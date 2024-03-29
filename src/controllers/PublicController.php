<?php

namespace riobasel\riometa\controllers;

use riobasel\riometa\RioMeta;

use Craft;
use craft\web\Controller;
use craft\helpers\UrlHelper;
use craft\helpers\App;






class PublicController extends Controller
{


    protected $allowAnonymous = true;


    public function actionIndex(){

        $currentSite = Craft::$app->getSites()->currentSite;

        $info = RioMeta::$plugin->metaservice->serializeMeta( $currentSite->id );
        if( !$info ){
            $info = [];
        }

        $isDev = App::env('ENVIRONMENT') === 'dev';
        $FRONTEND_URL = App::env('FRONTEND_URL');
        
        if($isDev){
          Craft::$app->getResponse()->getHeaders()->add( 'Access-Control-Allow-Origin', '*' );
        }else{
            Craft::$app->getResponse()->getHeaders()->add( 'Access-Control-Allow-Origin', '*' );
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
