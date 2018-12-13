<?php

use \Db\Migration\Migration;

class Faqs extends Migration
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
        $exists = $this->hasTable('faqs');
        if (!$exists) {
            $faqs = $this->table('faqs');
            $faqs->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime')        
                ->addColumn('faq_question', 'string',['limit' => 255])
                ->addColumn('faq_answer', 'text')
                ->save();
        }
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $exists = $this->hasTable('faqs');
        if ($exists) {
            $this->dropTable('faqs');
        }
    }
}
