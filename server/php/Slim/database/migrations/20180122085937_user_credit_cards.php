<?php

use \Db\Migration\Migration;

class UserCreditCards extends Migration
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
        $exists = $this->hasTable('user_credit_cards');
        if (!$exists) {
            $user_credit_cards = $this->table('user_credit_cards');
            $user_credit_cards->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('user_id', 'integer',['limit' => 20, 'null' => true])        
                ->addColumn('payment_gateway_id', 'integer',['limit' => 20, 'null' => true])        
                ->addColumn('payment_gateway_customer_id', 'integer',['limit' => 20, 'null' => true])   
                ->addColumn('token', 'string',['limit' => 255, 'null' => true])
                ->addColumn('credit_card_type', 'string',['null' => true])
                ->addColumn('masked_card_number', 'string',['null' => true])
                ->addColumn('name_on_the_card', 'string',['null' => true])
                ->addColumn('credit_card_expire', 'string',['null' => true])
                ->addColumn('cvv2', 'string',['null' => true])
                ->addColumn('is_primary', 'boolean',['default' => 0])
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('user_credit_cards');
        if ($exists) {
            $this->dropTable('user_credit_cards');
        }
    }
}
