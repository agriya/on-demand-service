<?php

use \Db\Migration\Migration;

class Services extends Migration
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
        $exists = $this->hasTable('services');
        if (!$exists) {
            $services = $this->table('services');
            $services->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('category_id', 'integer',['limit' => 20, 'null' => true])
                ->addColumn('booking_option_id', 'integer',['limit' => 20, 'null' => true, 'comment' => '(1 => TimeSlot, 2 => SingleDate, 3 => MultipleDate, 4 => Recurring, 5 => MultiHours)'])
                ->addColumn('is_need_user_location', 'boolean',['default' => 0])
                ->addColumn('is_enable_multiple_booking', 'boolean',['default' => 0])
                ->addColumn('name', 'string',['limit' => 250, 'null' => true])
                ->addColumn('slug', 'string',['limit' => 280, 'null' => true])
                ->addColumn('is_active', 'boolean',['default' => 1])
                ->addColumn('parent_id', 'integer',['null' => true])
                ->addColumn('icon_class', 'string',['limit' => 255, 'null' => true])
                ->addColumn('is_allow_to_get_number_of_items', 'biginteger',['limit' => 20, 'default' => 0])
                ->addColumn('label_for_number_of_item', 'string',['limit' => 255, 'null' => true])
                ->addColumn('maximum_number_to_allow', 'biginteger',['limit' => 20, 'default' => 10])
                ->addColumn('is_multiply_booking_amount_when_choosing_number_of_items', 'biginteger',['limit' => 20, 'default' => 0])
                ->addColumn('is_featured', 'boolean',['default' => 1])
                ->addIndex('category_id')
                ->addIndex('booking_option_id')
                ->addIndex('parent_id')
                ->addForeignKey('category_id','categories','id',['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
                ->addForeignKey('parent_id','services','id',['delete' => 'SET_NULL', 'update' => 'NO_ACTION'])
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('services');
        if ($exists) {
            $this->dropTable('services');
        }
    }
}
