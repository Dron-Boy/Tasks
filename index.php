<?php
    require_once dirname(__FILE__) . '/vendor/autoload.php';  
    use App\core\load as Load;
    require_once "config/config.php";
    require_once "core/views.php"; 
    $load = new Load;
    $load->load();
?>