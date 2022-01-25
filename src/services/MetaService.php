<?php
/**
 * riometa plugin for Craft CMS 3.x
 *
 * Rio Meta Data
 *
 * @link      julian@julian.julian
 * @copyright Copyright (c) 2022 Julian
 */

namespace riobasel\riometa\services;

use riobasel\riometa\riometa;
use riobasel\riometa\records\MetaRecord;

use Craft;
use craft\base\Component;

/**
 * riometa Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Julian
 * @package   riometa
 * @since     0.0.1
 */
class MetaService extends Component
{



    public function getMetaBySiteId( $siteId )
    {

        $record = MetaRecord::findOne(['siteId' => $siteId]);

        return $record;
    }



    public function getMetaBySiteHandle( $handle )
    {
        $siteId = Craft::$app->getSites()->getSiteByHandle( $handle )->id;

        if( !isset( $siteId ) ){
            return False;
        }

        $record = $this->getMetaBySiteId( $siteId );

        return $record;
    }



    public function saveMeta($data)
    {

        $record = $this->getMetaBySiteId($data['siteId']);

        if( !$record ){
            $record = new MetaRecord();
            $record->setAttribute('siteId', $data['siteId']);
        }

        $record->setAttribute('meta_description', $data['meta_description']);
        $record->setAttribute('meta_keywords', $data['meta_keywords']);

        $record->setAttribute('og_type', $data['og_type']);
        $record->setAttribute('og_image', $data['og_image']);

        $record->setAttribute('geo_region', $data['geo_region']);
        $record->setAttribute('geo_placename', $data['geo_placename']);
        $record->setAttribute('geo_latitude', $data['geo_latitude']);
        $record->setAttribute('geo_longitude', $data['geo_longitude']);

        $record->save();

        return true;
    }


    public function serializeMeta( $siteId )
    {

        $record = $this->getMetaBySiteId($siteId);

        if($record){
            if($record->meta_description){
                $result['meta_description'] = $record->meta_description;
            }
            if($record->meta_keywords){
                $result['meta_keywords'] = $record->meta_keywords;
            }
            if($record->og_type){
                $result['og_type'] = $record->og_type;
            }
            if($record->og_image){
                $result['og_image'] = $record->og_image;
            }
            if($record->geo_region){
                $result['geo_region'] = $record->geo_region;
            }
            if($record->geo_placename){
                $result['geo_placename'] = $record->geo_placename;
            }
            if($record->geo_position){
                $result['geo_latitude'] = $record->geo_latitude;
            }
            if($record->geo_position){
                $result['geo_longitude'] = $record->geo_longitude;
            }
        }

        return $record;
    }

}
