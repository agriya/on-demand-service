<?php

use \Db\Migration\Migration;

class EmailTemplates extends Migration
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
        $email_templates = $this->table('email_templates');
        $email_templates->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('name', 'string',['limit' => 255])
              ->addColumn('description', 'text',['null' => true])
              ->addColumn('subject', 'string',['null' => true])
              ->addColumn('body_content', 'text',['null' => true])
              ->addColumn('filename', 'string',['null' => true])
              ->addColumn('from_name', 'string',['null' => true])
              ->addColumn('info', 'string',['null' => true])
              ->addColumn('reply_to', 'string',['null' => true])
              ->addColumn('plugin', 'string',['null' => true,'limit' => 255])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('email_templates');
    }
}
