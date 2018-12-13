<?php

use \Db\Migration\Migration;

class ApnsDevices extends Migration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()     
    {
        $exists = $this->hasTable('apns_devices');
        if (!$exists) {
            $apns_devices = $this->table('apns_devices');
            $apns_devices->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('user_id', 'integer',['limit' => 20, 'null' => true])
                ->addColumn('appname', 'string',['limit' => 255, 'null' => true])
                ->addColumn('appversion', 'string',['limit' => 255, 'null' => true])
                ->addColumn('deviceuid', 'string',['limit' => 255, 'null' => true])
                ->addColumn('devicetoken', 'string',['limit' => 255, 'null' => true])
                ->addColumn('devicename', 'string',['limit' => 255, 'null' => true])
                ->addColumn('devicemodel', 'string',['limit' => 255, 'null' => true])
                ->addColumn('deviceversion', 'string',['limit' => 255, 'null' => true])
                ->addColumn('pushbadge', 'string',['limit' => 255, 'null' => true])
                ->addColumn('pushalert', 'string',['limit' => 255, 'null' => true])
                ->addColumn('pushsound', 'string',['limit' => 255, 'null' => true])
                ->addColumn('development', 'string',['limit' => 255, 'null' => true])
                ->addColumn('status', 'string',['limit' => 255, 'null' => true])
                ->addColumn('latitude', 'decimal')
                ->addColumn('longitude', 'decimal')
                ->addColumn('devicetype', 'biginteger',['limit' => 20, 'null' => true])
                ->addIndex('user_id')
                ->addForeignKey('user_id','users','id',['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('apns_devices');
        if ($exists) {
            $this->dropTable('apns_devices');
        }
    }
}
