<?php
    return $routs = [
        //контроллер tasks
        '/'=>[
                'tasks/index'  
            ],
        '/tasks/getSetTask/?([.*])' =>[
                '/tasks/getSetTask/?$1'
            ],
        '/tasks/getAcceptTask?([.*])' =>[
                '/tasks/getAcceptTask/?$1'
            ],
        '/tasks/getNoAcceptTask?([.*])' =>[
                '/tasks/getNoAcceptTask/?$1'
            ],
        '/tasks/getNoAcceptTask?([.*])' =>[
                '/tasks/getNoAcceptTask/?$1'
            ],
        '/tasks/getConsolidateTask?([.*])' =>[
                '/tasks/getConsolidateTask/?$1'
            ],
        '/tasks/getTodayTask?([.*])' =>[
                '/tasks/getTodayTask/?$1'
            ],
        '/tasks/getTodayTask?([.*])' =>[
                '/tasks/getTodayTask/?$1'
            ],
        '/tasks/getCompleteTask?([.*])' =>[
                '/tasks/getCompleteTask/?$1'
            ],
        '/tasks/findTask?([.*])' =>[
                '/tasks/findTask/?$1'
            ],
        '/tasks/getById?([.*])' =>[
                '/tasks/getById/?$1'
            ],
        '/tasks/addComment?([.*])' =>[
                '/tasks/addComment/?$1',
                '/users/users'
            ],
        '/tasks/addTask?([.*])' =>[
                '/tasks/addTask/?$1',
                '/users/users'
            ],
        '/tasks/updateTask?([.*])' =>[
                '/tasks/updateTask/?$1',
                '/users/users'
            ],
        '/tasks/updateStatusTask?([.*])' =>[
                '/tasks/updateStatusTask/?$1',
                '/users/users'
            ],
        '/tasks/deleteTask?([.*])' =>[
                '/tasks/deleteTask/?$1',
                '/users/users'
            ],
        '/tasks/getSubtask?([.*])' =>[
                '/tasks/getSubtask/?$1',
                '/users/users'
            ],
        '/tasks/getNoCompleteTask?([.*])' =>[
                '/tasks/getNoCompleteTask/?$1',
            ],
        '/tasks/asseptTask?([.*])' =>[
                '/tasks/asseptTask/?$1',
                '/users/users'
            ],
        //контроллер uasers
        '/users/getCurrentUser?([.*])'=>[
                '/users/getCurrentUser/?$1',
                '/departments/departments'
            ],
        '/users/finduser?([.*])'=>[
                '/users/finduser/?$1',
            ],
        //Контроллер department
        '/departments/getDepartmentById?([.*])' => [
            '/departments/getDepartmentById/?$1'
        ],
    ];
?>