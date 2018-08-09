<div class="header">
    <div class="main">
         <div class="logo">
             <a href="">MYCRM</a>
         </div>
         <div class="nav">
             <ul>
                 <li>
                     <a><i class="fa fa-list-alt" aria-hidden="true"></i>Тип задачи</a>
                     <ul>
                        <li><a class="link" url ='/tasks/index'>Все задачи</a></li>
                        <li><a class="link" url ='/tasks/getSetTask'>Поставленные задачи</a></li>
                        <li><a class="link" url ='/tasks/getAcceptTask'>Принятые задачи</a></li>
                        <li><a class="link" url ='/tasks/getNoAcceptTask'>Не принятые задачи</a></li>
                     </ul>
                 </li>
                 <li><a class="link" url = "/tasks/getConsolidateTask"><i class="fa fa-paperclip" aria-hidden="true"></i>Закрепленые</a></li>
                 <li><a class="link" url ='/tasks/getTodayTask'><i class="fa fa-calendar" aria-hidden="true"></i>На сегодня</a></li>
                 <li><a class="link" url ='/tasks/getCompleteTask'><i class="fa fa-check-square-o" aria-hidden="true"></i>Выполненные</a></li>
                 <li><a class="link" url ='/tasks/getNoCompleteTask'><i class="fa fa-times" aria-hidden="true"></i></i>Не выполненные</a></li>
             </ul>
         </div>
         <div class="find">
             <form action="">
                 <i class="fa fa-search" aria-hidden="true"></i>
                 <input type="text" url="/tasks/findTask/?search="  class="find_text" value="" placeholder="" name="">
             </form>
         </div>
         <div class="clear"></div>
    </div>
</div>
