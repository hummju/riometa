<?php

namespace riobasel\riometa\controllers;

use riobasel\riometa\riometa;

use Craft;
use craft\web\Controller;
use craft\helpers\UrlHelper;






class EditController extends Controller
{

    public function actionIndex()
    {

        $this->requirePermission('editMetaData');

        $params = Craft::$app->urlManager->getRouteParams();

        $siteHandle;

        if( isset($params['siteHandle']) ){
            $siteHandle = $params['siteHandle'];
        }else{
            $siteHandle = "default";
        }

        $site = Craft::$app->getSites()->getSiteByHandle( $siteHandle );

        if( !$site ){
            return Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('riometa'))->send();
        }

        $meta = riometa::$plugin->metaservice->getMetaBySiteHandle( $siteHandle );



        $result = $this->renderTemplate(
          'riometa/edit',
          [

                'namespace' => 'data',
                'siteId' => $site->id,
                'fields' => [

                    'meta_description' => 		$meta ? $meta->meta_description: "",
                    'meta_keywords' => 			$meta ? $meta->meta_keywords: "",

                    'og_type' => 				$meta ? $meta->og_type: "",
                    'og_image' => 				$meta ? $meta->og_image: "",

                    'geo_region' => 			$meta ? $meta->geo_region: "",
                    'geo_placename' => 			$meta ? $meta->geo_placename: "",
                    'geo_latitude' => 			$meta ? $meta->geo_latitude: "",
                    'geo_longitude' => 			$meta ? $meta->geo_longitude: ""

            	],
      		]
        );

        return $result;
    }



    public function actionSave()
    {

        $this->requirePermission('editMetaData');
        $this->requirePostRequest();

		$craft = \Craft::$app;
		$data = $craft->request->getRequiredBodyParam('data');

		if (riometa::$plugin->metaservice->saveMeta($data))
		{
			$craft->session->setNotice(
				\Craft::t('riometa', 'Updated Metadata')
			);
		}
        else
        {
			$craft->session->setNotice(
				\Craft::t('riometa', 'Could not save Metadata')
			);
		}

  		$this->redirectToPostedUrl();

    }





}
