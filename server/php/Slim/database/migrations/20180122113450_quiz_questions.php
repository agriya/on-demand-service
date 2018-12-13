<?php

use \Db\Migration\Migration;

class QuizQuestions extends Migration
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
        $quiz_questions = $this->table('quiz_questions');
        $quiz_questions
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('quiz_id', 'integer', ['null' => true])
              ->addColumn('question','string', ['limit' => 255])
              ->addIndex('quiz_id')
              ->addForeignKey('quiz_id','quizzes','id',['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('quiz_questions');
    }
}
