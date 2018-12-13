<?php

use \Db\Migration\Migration;

class MoneyTransferAccounts extends Migration
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
        $money_transfer_accounts = $this->table('money_transfer_accounts');
        $money_transfer_accounts->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('user_id', 'integer', ['limit' => 20, 'null' => true])              
              ->addColumn('account', 'string', ['limit' => 255, 'default' => 0])              
              ->addColumn('is_primary', 'boolean', ['default' => 0])              
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('money_transfer_accounts');
    }
}
