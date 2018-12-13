<?php

use \Db\Migration\Migration;

class QuizQuestionAnsweroptions extends Migration
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
        $quiz_question_answer_options = $this->table('quiz_question_answer_options');
        $quiz_question_answer_options
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')        
              ->addColumn('quiz_id', 'integer', ['null' => true])
              ->addColumn('quiz_question_id', 'integer', ['null' => true])
              ->addColumn('option','string', ['limit' => 255, 'null' => true])
              ->addColumn('is_correct_answer','boolean', ['default' => 0])
              ->addIndex('quiz_question_id')
              ->addIndex('quiz_id')
              ->addForeignKey('quiz_id','quizzes','id',['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->addForeignKey('quiz_question_id','quiz_questions','id',['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('quiz_question_answer_options');
    }
}
