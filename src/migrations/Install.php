<?php
/**
 * riometa plugin for Craft CMS 3.x
 *
 * Rio Meta Data
 *
 * @link      julian@julian.julian
 * @copyright Copyright (c) 2022 Julian
 */

namespace riobasel\riometa\migrations;

use riobasel\riometa\riometa;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * riometa Install Migration
 *
 * If your plugin needs to create any custom database tables when it gets installed,
 * create a migrations/ folder within your plugin folder, and save an Install.php file
 * within it using the following template:
 *
 * If you need to perform any additional actions on install/uninstall, override the
 * safeUp() and safeDown() methods.
 *
 * @author    Julian
 * @package   riometa
 * @since     0.0.1
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates the tables needed for the Records used by the plugin
     *
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

    // riometa_riometa table
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%riometa_riometa}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%riometa_riometa}}',
                [

                    'id' => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),

                    'siteId' =>             $this->integer()->notNull(),

                    'meta_description' =>   $this->string(165),
                    'meta_keywords' =>      $this->string(255),

                    'og_type' =>            $this->string(),
                    'og_title' =>           $this->string(),
                    'og_url' =>             $this->string(),
                    'og_image' =>           $this->string(),
                    'og_description' =>     $this->string(),
                    'og_locale' =>          $this->string(),

                    'geo_region' =>         $this->string(),
                    'geo_placename' =>      $this->string(),
                    'geo_position' =>       $this->string(),
                    'geo_latitude' =>       $this->float(),
                    'geo_longitude' =>      $this->float(),

                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * Creates the indexes needed for the Records used by the plugin
     *
     * @return void
     */
    protected function createIndexes()
    {
    // riometa_riometa table
        $this->createIndex(
            $this->db->getIndexName(
                '{{%riometa_riometa}}',
                'some_field',
                true
            ),
            '{{%riometa_riometa}}',
            'id',
            true
        );
        // Additional commands depending on the db driver
        switch ($this->driver) {
            case DbConfig::DRIVER_MYSQL:
                break;
            case DbConfig::DRIVER_PGSQL:
                break;
        }
    }

    /**
     * Creates the foreign keys needed for the Records used by the plugin
     *
     * @return void
     */
    protected function addForeignKeys()
    {
    // riometa_riometa table
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%riometa_riometa}}', 'siteId'),
            '{{%riometa_riometa}}',
            'siteId',
            '{{%sites}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * Populates the DB with the default data.
     *
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * Removes the tables needed for the Records used by the plugin
     *
     * @return void
     */
    protected function removeTables()
    {
    // riometa_riometa table
        $this->dropTableIfExists('{{%riometa_riometa}}');
    }
}
