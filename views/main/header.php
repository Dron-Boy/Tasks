<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="shortcut icon" type="image/png" href="/favicon.ico" />
		<title>Задачник</title>
		<link href='/css/style.css' rel='stylesheet' />
		<link rel="stylesheet" href="/css/font-awesome-4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript" src="/js/script.js"></script>
	</head>
	<body>
	<div class="modal_add_task_overlay"></div> 
    <div class="modal_add_task">
        <p>Добавить задачу</p> 
        <form action="/tasks/addTask" method="post">
            <input type="text" name="name" autocomplete="off" value="" placeholder="Название задачи" required><br><br>
            <span>Описание задачи</span>
            <textarea name="description" autocomplete="off" placeholder="Описание задачи"></textarea>
            <span>Срок выполнения</span>
            <input type="date" name="date_end" id='date_end'><br><br>
            <span>Введите имя или e-mail исполнителя</span>
            <input type="text" url="/users/finduser/?name=" value="" autocomplete = "off" name="user" id="executor" placeholder="Исполнитель">
            <input type="hidden" name="id_user" id="id_user">
            <input type="hidden" name="parent_id" value="0">
            <div class="list"></div>
            <br><br>
            <input type="submit" id="continue" name="go" value="Готово">
        </form>
    </div>
    <div class="modal_add_user">
        <i class="fa fa-times closed_user_add" aria-hidden="true"></i>
        <form action="/users/addUser">
            <input name='name' type="text" Placeholder="login">
            <input name='password' type="password" Placeholder="Пароль">
            <i class="fa fa-eye view_pass" aria-hidden="true"></i>
            <span id="generate_password" url="/users/generatePass"><i class="fa fa-cube" aria-hidden="true"></i> Сгенерировать пароль</span>
            <input name='first_name_user' type="text" Placeholder="Имя">    
            <input name='last_name_user' type="text" Placeholder="Фамилия">    
            <input name='email' type="email" Placeholder="E-mail">    
            <input type="submit" value="Добавить пользователя" id="add_user_send">
        </form>
    </div>
    <div class="content">
    <div class="menu_seting">
        <ul>
            <li>Настройка 1</li>
            <li>Настройка 2</li>
            <li>Настройка 3</li>
            <li>Настройка 4</li>
            <li>Настройка 5</li>
        </ul>
    </div>
    <div class="main">