<?php

use \Db\Migration\Migration;

class TopListingPaymentLogs extends Migration
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
        $top_listing_payment_logs = $this->table('top_listing_payment_logs');
        $top_listing_payment_logs
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer',['null' => true])
              ->addColumn('paid_top_listing_amount', 'decimal',['precision' => 10, 'scale' => 2, 'default' => '0.00'])
              ->addColumn('top_listing_paid_on', 'datetime',['null' => true])
              ->addColumn('expiry_on', 'datetime',['null' => true])
              ->addColumn('is_active', 'boolean',['default' => false])
              ->addIndex('user_id')
              ->addForeignKey('user_id','users','id',['delete' => 'SET_NULL','update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('top_listing_payment_logs');
    }
}
