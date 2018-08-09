
$(document).ready(function(){
    //Отправка всех ajax запросов
    function ajax_send(url,data='',type='POST'){
        var result;
        $.ajax({
            url: url,
            type: type,
            async: false,
            data:data,
            success:function(data){
               result =  data;
            },
        });
        return result;
    }
   /* var granted = true;
    function requestPermission() {
        return new Promise(function(resolve, reject) {
            const permissionResult = Notification.requestPermission(function(result) {
                // Поддержка устаревшей версии с функцией обратного вызова.
                resolve(result);
            });

            if (permissionResult) {
                permissionResult.then(resolve, reject);
            }
        })
        .then(function(permissionResult) {
            if (permissionResult !== 'granted') {
                granted = false;
                //throw new Error('Permission not granted.');
            }
        });
    }
    Notification.requestPermission();*/
    //Функция получения Задач
    function getTask(url){
        $('.center_content').html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw load"></i>')
        var data = ajax_send(url);
        data = JSON.parse(data);
        setTimeout(function(){
            $('.center_content').remove();
            $('.left_content').after(data.content);
        },300)
    }
    
    setInterval(function(){
        var data = ajax_send('/tasks/getRecallTask');
        data = JSON.parse(data);
        if(data.response){
            /*if ((('serviceWorker' in navigator) || ('PushManager' in window)) && granted == true) {
                var mailNotification = new Notification('Напоминание', {
                    tag : "Напоминание",
                    body : data.response,
                    icon : ""
                });
                setTimeout(function(){
                    mailNotification.close();
                },5000)
            }else{
               
            }*/
             alert(data.response);
        }else{
            console.log('Напоминаний нет')
        }
    },60000)    
    //Функция получения обытий
    function getNotification(url){
        var data = ajax_send(url);
        data = JSON.parse(data);
        setTimeout(function(){
            $('.modal_notification .content_notification').html(data.content);
        },300)
    }
    
    $('body').on('click','#notification',function(){
        if($('.modal_notification').attr('show') == '0'){
            $('.modal_notification').fadeIn();
            $('.modal_notification').attr('show',1);
            $('.modal_notification .content_notification').html('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw load"></i>')
            var url = $(this).attr('url');
            getNotification(url);
        }else{
            $('.modal_notification').fadeOut();
            $('.modal_notification').attr('show',0);
        }
    })
    
    //Вызов метода получения задач
    $( "body" ).on( "click",'.link', function() {
        getTask($(this).attr('url'));
    })
    
    //Вызов поиска
    $('.find_text').keyup(function(){
        if($(this).val()){
            getTask($(this).attr('url')+''+$(this).val());
        }
    })
    
    $('body').on('click', '.create_date > i',function(){
        $(this).parent('.create_date').children('.recall').fadeIn();
    })
    $('body').on('click', '.closed_recall',function(){
        $(this).closest('.recall').fadeOut();
    })
    $('body').on('click', '#recall',function(e){
        e.preventDefault();
        var url = $(this).parent('form').prop('action');
        var data = $(this).parent('form').serialize();
        data = ajax_send(url,data);
        data = JSON.parse(data);
        $(this).closest('.recall').fadeOut();
        alert(data.response)
        getTask('/tasks/index');
    })
    
    $('#generate_password').click(function(){
        var url = $(this).attr('url');
        var data = JSON.parse(ajax_send(url));
        $(this).parent('form').children('input[name="password"]').val(data.password);
    })
    
    $('body').on('click', '#add_user',function(e){
        $('.modal_add_user').animate({
            top:'40%',
        },500)
    })
    
    $('body').on('click', '#folders',function(){
        if($(this).hasClass('fa-folder-o')){
            $(this).prop('class','fa fa-folder-open-o');
        }else{
            $(this).prop('class','fa fa-folder-o');
        }
    })
    $('#add_user_send').click(function(e){
        e.preventDefault();
        var url = $(this).parent('form').prop('action');
        var data = $(this).parent('form').serialize();
        data = JSON.parse(ajax_send(url,data));
        if(data.success){
            alert(data.success)
            $(this).closest('.modal_add_user').animate({
                top:'-500px',
            },500)
            $(this).parent('form')[0].reset();
        }else{
            alert(data.error)
        }
    })
    $('.closed_user_add').click(function(){
        $('.modal_add_user').animate({
            top:'-500px',
        },500)
    })
    $( ".view_pass" ).hover(
        function() {
            $(this).parent('form').children('input[name="password"]').prop('type','text')
        }, function() {
            $(this).parent('form').children('input[name="password"]').prop('type','password')
        }
    );
    //Добовление новой задачи
    $('#continue').click(function(e){
        e.preventDefault();
        var url = $(this).parent('form').prop('action');
        var data = $(this).parent('form').serialize();
        var obj = $(this).parent('form');
        data = ajax_send(url,data);
        data = JSON.parse(data);
        if(data.error){
             alert(data.error);
        }else{
             alert(data.response);
             $(obj)[0].reset();
             $('.modal_add_task_overlay').trigger('click');
             getTask('/tasks/index');
        } 
    })
    
    //Добовление нового комментария
    $( "body" ).on( "click",'#send_com', function(e) {
        e.preventDefault();
        var url = $(this).parent('form').prop('action');
        var data = $(this).parent('form').serialize();
        var obj = $(this).parent('form');
        data = ajax_send(url,data);
        data = JSON.parse(data);
        console.log(data)
        if(data.error){
             alert(data.error);
        }else{
             alert(data.response);
             $(obj)[0].reset();
             getTask('/tasks/index');
             setTimeout(function(){
                $('#task'+data.id_task).trigger('click')
             },350)
        } 
    })
    
    //Ищем пользователя из нужной нам компании
    $( "body" ).on( "keyup",'#executor', function() {
        if(!$(this).val()){
            $('.modal_add_task .list').html('')
        }
        var url = $(this).attr('url')+''+$(this).val();
        var obj = $(this);
        var data = ajax_send(url);
        data = JSON.parse(data);
        $('.modal_add_task .list').html(data.content)
    })
    
    
    //Выбираем нужного пользователя
    $( "body" ).on( "click",'.list .user_item', function() {
            $('#executor').val($(this).html())
            $('#id_user').val($(this).attr('id_user'))
            $('.modal_add_task .list').html('')
    })
    
    $( "body" ).on( "click",'.user_info', function() {
        if(!$(this).hasClass('open')){
            $(this).addClass('open')
            var url = $(this).attr('url');
            var data = JSON.parse(ajax_send(url,data));
            $(this).after(data.content)
        }else{
            $(this).removeClass('open')
            $('.user_info_block').remove();
        }
    })
    
    //Открываем модальное окно для добавления задачи
    $( "body" ).on( "click",'#add_task', function() {
        $('input[name="parent_id"]').val($(this).attr('parent_id'))
        if($(this).attr('parent_id') != 0){
            $('#id_user').val($(this).attr('id_user'))  
            $('#executor').val($(this).attr('name_user'))  
            $('#executor').prop('disabled','disabled')  
            $('#date_end').prop('max',$(this).attr('date_end'));
        }else{
            $('#date_end').removeAttr('max');
            $('#executor').removeAttr('disabled');
        }
        $('.modal_add_task_overlay').fadeIn();
        setTimeout(function(){
            $('.modal_add_task').fadeIn();
        },500)
        window.addEventListener("keydown", function(e){
            if (e.keyCode == 27) {
                $('.modal_add_task_overlay').trigger('click');
            }
        }, true);
    })
    
    //Скрываем модальное окно
    $('.modal_add_task_overlay').click(function(){
        if(insert_mous('.modal_add_task') == true){
            return false;
        }
        $('.modal_add_task').fadeOut();
        setTimeout(function(){
            $('.modal_add_task_overlay').fadeOut();
        },500)
    })
    
    //Скрываем/расскрываем меню слева
    $( "body" ).on( "click",'#show_menu', function() {
        if( $('.left_content').attr('show') == '1'){
            $('.left_content').css('margin-left','-'+$('.left_content').css('width')+'');
            $('.center_content').css('width','97%')
            $('.left_content').attr('show',0);
        }else{
            $('.left_content').css('margin-left','0');
            $('.center_content').css('width','77%')
            $('.left_content').attr('show',1)
        }
    })
    //Функция проверяет наличие мыши над объектом
    function insert_mous(el){
        var ins = false;
        $( "body" )
            .on( "mouseenter",el, function() {
                ins = true;
            })
            .on( "mouseleave",el, function() {
                ins = false;
            })
            return ins;
    }
    //Разворачиваем задачи
    $( "body" ).on( "click",'.task .title', function() {
        if(insert_mous('.title >i') == true){
            return false;
        }
        var parent = $(this).parent('.task');
        if($(parent).hasClass('open')){
            $(parent).find('.task_detail').animate({
                'height':'0px'
            },500)
            $(parent).removeClass('open');          
        }else{
            var id = $(parent).attr('task_id')
            $('.zadachi').after('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw load"></i>')
            data = JSON.parse(ajax_send('/tasks/getSubtask',{id:id}));
            setTimeout(function(){
                $('.left_instr').remove();
                var show = $('.left_content').attr('show');
                $('.left_content').remove();
                $('.header').after(data.content);
                if(show == 0){
                    $('.left_content').css('display','none');
                    $('.left_content').css('margin-left','-'+$('.left_content').css('width')+'')
                    $('.left_content').attr('show',0)
                    setTimeout(function(){
                        $('.left_content').css('display','block');
                    },500)
                }
            },300)
            $(parent).addClass('open');
            $(parent).find('.task_detail').animate({
                'height':'400px'
            },500)
        }
    })
})