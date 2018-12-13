<?php
$menus = array(
    'OnlineAssessment' => array(
        'title' => 'Online Assessment',
        'icon_template' => '<span class="fa fa-dashboard fa-fw"></span>',
        'child_sub_menu' => array(
            'quizzes' => array(
                'title' => 'Topics',
                'icon_template' => '<span class="fa fa-newspaper-o"></span>',
                'suborder' => 1
            ) ,
            'quiz_questions' => array(
                'title' => 'Questions',
                'icon_template' => '<span class="fa fa-question"></span>',
                'suborder' => 2
            ) ,
            /*'quiz_question_answer_options' => array(
                'title' => 'Topic Question Answer Options',
                'icon_template' => '<span class="fa fa-barcode"></span>',
                'suborder' => 19,
            ) ,*/
        ) ,
        'order' => 5
    ) ,
);
$tables = array(
    'quizzes' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'description',
                    'label' => 'Description',
                ) ,
                3 => array(
                    'name' => 'display_order',
                    'label' => 'Display Order',
                ) ,
                4 => array(
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'Topics',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'display_order',
                    'label' => 'Display Order',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'display_order',
                    'label' => 'Display Order',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
            ) ,
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'description',
                    'label' => 'Description',
                ) ,
                3 => array(
                    'name' => 'display_order',
                    'label' => 'Display Order',
                ) ,
            ) ,
        ) ,
    ) ,
    'quiz_questions' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'quiz.name',
                    'label' => 'Topic',
                ) ,
                2 => array(
                    'name' => 'question',
                    'label' => 'Question',
                ) ,
                3 => array(
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,
            ) ,
            'title' => 'Topic Questions',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ) ,
                1 => array(
                    'name' => 'quiz_id',
                    'label' => 'Topic',
                    'targetEntity' => 'quizzes',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'quiz_id',
                    'label' => 'Topic',
                    'targetEntity' => 'quizzes',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'question',
                    'label' => 'Question',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'answer_options',
                    'label' => 'Answer Options (\n)',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'current_answer',
                    'label' => 'Enter current answer',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'quiz_id',
                    'label' => 'Topic',
                    'targetEntity' => 'quizzes',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'question',
                    'label' => 'Question',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'answer_options',
                    'label' => 'Answer Options (\n)',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'current_answer',
                    'label' => 'Enter current answer',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'quiz_id',
                    'label' => 'Topic',
                    'targetEntity' => 'quizzes',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getParentQuiz'
                ) ,
                2 => array(
                    'name' => 'question',
                    'label' => 'Question',
                ) ,
                4 => array (
                'name' => 'Answer Option',
                'label' => 'Answer Option',
                'targetEntity' => 'quiz_question_answer_options',
                'targetReferenceField' => 'quiz_question_id',
                'targetFields' => array (
                    0 => 
                    array (
                        'name' => 'id',
                        'label' => 'ID',
                        'isDetailLink' => true,
                    ),
                    1 => 
                    array (
                    'name' => 'option',
                    'label' => 'Option',
                    ),
                    2 => 
                    array (
                        'name' => 'is_correct_answer',
                        'label' => 'Correct Answer',
                        'type' => 'boolean'
                    ),
                ) ,
                'map' => array (
                    0 => 'truncate',
                ) ,
                'type' => 'referenced_list',
                'perPage' => 10
                ) , 
            ) ,
        ) ,
    ) ,
    'quiz_question_answer_options' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'quiz.name',
                    'label' => 'Topic',
                ) ,
                2 => array(
                    'name' => 'quiz_question.question',
                    'label' => 'Topic Question',
                ) ,
                3 => array(
                    'name' => 'option',
                    'label' => 'Option',
                ) ,
                4 => array(
                    'name' => 'is_correct_answer',
                    'label' => 'Correct Answers?',
                ) ,
                5 => array(
                    'name' => 'created_at',
                    'label' => 'Created On',
                ) ,                
            ) ,
            'title' => 'Topic Question Answer Options',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '',
                ) ,
                1 => array(
                    'name' => 'quiz_id',
                    'label' => 'Topic',
                    'targetEntity' => 'quizzes',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array(
                    'name' => 'quiz_question_id',
                    'label' => 'Topic Question',
                    'targetEntity' => 'quiz_questions',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'quiz_id',
                    'label' => 'Topic',
                    'targetEntity' => 'quizzes',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'quiz_question_id',
                    'label' => 'Topic Question',
                    'targetEntity' => 'quiz_questions',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'option',
                    'label' => 'Option',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'is_correct_answer',
                    'label' => 'Correct Answers?',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'quiz_id',
                    'label' => 'Topic',
                    'targetEntity' => 'quizzes',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'quiz_question_id',
                    'label' => 'Topic Question',
                    'targetEntity' => 'quiz_questions',
                    'targetField' => 'question',
                    'type' => 'reference',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'option',
                    'label' => 'Option',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'is_correct_answer',
                    'label' => 'Correct Answers?',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'quiz_id',
                    'label' => 'Topic',
                    'targetEntity' => 'quizzes',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'singleApiCall' => 'getParentQuiz'
                ) ,
                2 => array(
                    'name' => 'quiz_id',
                    'label' => 'Topic',
                    'targetEntity' => 'quiz_questions',
                    'targetField' => 'question',
                    'type' => 'reference',
                    'singleApiCall' => 'getParentQuizQuestion'
                ) ,
                2 => array(
                    'name' => 'quiz_question_id',
                    'label' => 'Topic Question',
                    'targetEntity' => 'quiz_questions',
                    'targetField' => 'question',
                    'type' => 'reference',
                    'singleApiCall' => 'getParentQuizQuestion'
                ) ,
                3 => array(
                    'name' => 'option',
                    'label' => 'Option',
                ) ,
                4 => array(
                    'name' => 'is_correct_answer',
                    'label' => 'Correct Answers?',
                ) ,
            ) ,
        ) ,
    ) ,
);
