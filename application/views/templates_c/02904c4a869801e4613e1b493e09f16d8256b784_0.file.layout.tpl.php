<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2024-11-17 13:52:47
  from "C:\wamp64\www\WORKS\stateside\application\views\login\layout.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-23',
  'unifunc' => 'content_6739bcef864d26_55667722',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '02904c4a869801e4613e1b493e09f16d8256b784' => 
    array (
      0 => 'C:\\wamp64\\www\\WORKS\\stateside\\application\\views\\login\\layout.tpl',
      1 => 1727327561,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layout/flash_message.tpl' => 1,
  ),
),false)) {
function content_6739bcef864d26_55667722 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo assets_url('images/ico/apple-icon-120.png');?>
">
    <link rel="icon" type="image/png" href="<?php echo assets_url('images/ico/favicon.ico');?>
">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />   
    <title> <?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 | <?php echo $_smarty_tpl->tpl_vars['site_details']->value['name'];?>
</title>
    
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_64676536739bcef837df3_36429203', 'header');
?>

    <link rel="shortcut icon" href="<?php echo assets_url();?>
images/logo/<?php echo $_smarty_tpl->tpl_vars['site_details']->value['favicon'];?>
" />
</head>

<body class="off-canvas-sidebar">   
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white">
        <div class="container"> 
            <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
                <span class="navbar-toggler-icon icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav"> 
                    
                    <?php if (log_user_id()) {?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('login');?>
" class="nav-link">
                            <i class="material-icons">home</i> <?php echo lang('dashboard');?>

                        </a>
                    </li> 
                    <?php } else { ?>
                    <?php if (current_uri() == 'login/index') {?>
                    <li class="nav-item row">
                        <a href="<?php echo base_url('customer-login');?>
" class="nav-link">
                            <i class="material-icons">fingerprint</i> Customer Login
                        </a>
                        <a href="<?php echo base_url('forgot');?>
" class="nav-link" target="new">
                            <i class="material-icons">lock_open</i> Forgot Password
                        </a>
                    </li>
                    <?php } else { ?>
                    <li class="nav-item <?php if (current_uri() == 'login/index' || current_uri() == '') {?> active <?php }?> ">
                        <a href="<?php echo base_url('login');?>
" class="nav-link">
                            <i class="material-icons">fingerprint</i> <?php echo lang('login');?>

                        </a>
                    </li>
                    <?php }?>
                    <?php }?> 
                    
                    <?php if (current_uri() == 'forgot/index') {?>
                    <li class="nav-item ">
                        <a href="<?php echo base_url('forgot');?>
" class="nav-link" target="new">
                            <i class="material-icons">lock_open</i> Forgot Password
                        </a>
                    </li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <div class="wrapper wrapper-full-page">

        <div class="page-header login-page header-filter" filter-color="black" style="background-image: url('<?php echo assets_url();?>
bootv4/img/login.jpg'); background-size: cover; background-position: top center;"> 
            <div class="container">
                <?php $_smarty_tpl->_subTemplateRender("file:layout/flash_message.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_1871871946739bcef853a72_37545325', 'body');
?>

            </div>
        </div>
    </div> 

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_14035537466739bcef8552c6_12424120', 'footer');
?>

</body>

</html><?php }
/* {block 'header'} */
class Block_64676536739bcef837df3_36429203 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_64676536739bcef837df3_36429203',
  ),
);
public $callsChild = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="<?php echo assets_url();?>
bootv4/css/font-awesome.min.css">
    <link href="<?php echo assets_url();?>
bootv4/css/backoffice.min.css" rel="stylesheet" />
    <link href="<?php echo assets_url();?>
bootv4/css/style.css" rel="stylesheet" />
    <!-- CSS Files --> 
    <?php 
$_smarty_tpl->inheritance->callChild($_smarty_tpl, $this);
?>

    <!-- END Page Level CSS--> 
    <?php
}
}
/* {/block 'header'} */
/* {block 'body'} */
class Block_1871871946739bcef853a72_37545325 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_1871871946739bcef853a72_37545325',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'body'} */
/* {block 'footer'} */
class Block_14035537466739bcef8552c6_12424120 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_14035537466739bcef8552c6_12424120',
  ),
);
public $callsChild = 'true';
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
 
    <?php echo '<script'; ?>
 src="<?php echo assets_url();?>
bootv4/js/core/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo assets_url();?>
bootv4/js/core/popper.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo assets_url();?>
bootv4/js/core/bootstrap-material-design.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo assets_url();?>
bootv4/js/plugins/perfect-scrollbar.jquery.min.js"><?php echo '</script'; ?>
> 
    <?php echo '<script'; ?>
 async defer src="<?php echo assets_url();?>
bootv4/js/plugins/buttons.js"><?php echo '</script'; ?>
>
    <!-- Chartist JS -->
    <?php echo '<script'; ?>
 src="<?php echo assets_url();?>
bootv4/js/plugins/chartist.min.js"><?php echo '</script'; ?>
>
    <!--  Notifications Plugin    -->
    <?php echo '<script'; ?>
 src="<?php echo assets_url();?>
bootv4/js/plugins/bootstrap-notify.js"><?php echo '</script'; ?>
> 
    <?php echo '<script'; ?>
 src="<?php echo assets_url();?>
bootv4/js/core/material-dashboard.min.js?v=2.1.0" type="text/javascript"><?php echo '</script'; ?>
>
    
    <?php 
$_smarty_tpl->inheritance->callChild($_smarty_tpl, $this);
?>
 

    <?php echo '<script'; ?>
>
        $(document).ready(function() {
            $().ready(function() {
                $sidebar = $('.sidebar');

                $sidebar_img_container = $sidebar.find('.sidebar-background');

                $full_page = $('.full-page');

                $sidebar_responsive = $('body > .navbar-collapse');

                window_width = $(window).width();

                fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

                if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
                    if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
                        $('.fixed-plugin .dropdown').addClass('open');
                    }

                }

                $('.fixed-plugin a').click(function(event) { 
                    if ($(this).hasClass('switch-trigger')) {
                        if (event.stopPropagation) {
                            event.stopPropagation();
                        } else if (window.event) {
                            window.event.cancelBubble = true;
                        }
                    }
                });

                $('.fixed-plugin .active-color span').click(function() {
                    $full_page_background = $('.full-page-background');

                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');

                    var new_color = $(this).data('color');

                    if ($sidebar.length != 0) {
                        $sidebar.attr('data-color', new_color);
                    }

                    if ($full_page.length != 0) {
                        $full_page.attr('filter-color', new_color);
                    }

                    if ($sidebar_responsive.length != 0) {
                        $sidebar_responsive.attr('data-color', new_color);
                    }
                });

                $('.fixed-plugin .background-color .badge').click(function() {
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');

                    var new_color = $(this).data('background-color');

                    if ($sidebar.length != 0) {
                        $sidebar.attr('data-background-color', new_color);
                    }
                });

                $('.fixed-plugin .img-holder').click(function() {
                    $full_page_background = $('.full-page-background');

                    $(this).parent('li').siblings().removeClass('active');
                    $(this).parent('li').addClass('active');


                    var new_image = $(this).find("img").attr('src');

                    if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                        $sidebar_img_container.fadeOut('fast', function() {
                            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                            $sidebar_img_container.fadeIn('fast');
                        });
                    }

                    if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

                        $full_page_background.fadeOut('fast', function() {
                            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
                            $full_page_background.fadeIn('fast');
                        });
                    }

                    if ($('.switch-sidebar-image input:checked').length == 0) {
                        var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
                        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

                        $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                        $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
                    }

                    if ($sidebar_responsive.length != 0) {
                        $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
                    }
                });

                $('.switch-sidebar-image input').change(function() {
                    $full_page_background = $('.full-page-background');

                    $input = $(this);

                    if ($input.is(':checked')) {
                        if ($sidebar_img_container.length != 0) {
                            $sidebar_img_container.fadeIn('fast');
                            $sidebar.attr('data-image', '#');
                        }

                        if ($full_page_background.length != 0) {
                            $full_page_background.fadeIn('fast');
                            $full_page.attr('data-image', '#');
                        }

                        background_image = true;
                    } else {
                        if ($sidebar_img_container.length != 0) {
                            $sidebar.removeAttr('data-image');
                            $sidebar_img_container.fadeOut('fast');
                        }

                        if ($full_page_background.length != 0) {
                            $full_page.removeAttr('data-image', '#');
                            $full_page_background.fadeOut('fast');
                        }

                        background_image = false;
                    }
                });

                $('.switch-sidebar-mini input').change(function() {
                    $body = $('body');

                    $input = $(this);

                    if (md.misc.sidebar_mini_active == true) {
                        $('body').removeClass('sidebar-mini');
                        md.misc.sidebar_mini_active = false;

                        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

                    } else {

                        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

                        setTimeout(function() {
                            $('body').addClass('sidebar-mini');

                            md.misc.sidebar_mini_active = true;
                        }, 300);
                    }

                    var simulateWindowResize = setInterval(function() {
                        window.dispatchEvent(new Event('resize'));
                    }, 180);

                    setTimeout(function() {
                        clearInterval(simulateWindowResize);
                    }, 1000);
                });
            });
});
<?php echo '</script'; ?>
>  
<?php echo '<script'; ?>
>
    $(document).ready(function() {
        md.checkFullPageBackgroundImage();
        setTimeout(function() {
            $('.card').removeClass('card-hidden');
        }, 700);
    });
<?php echo '</script'; ?>
>
<?php
}
}
/* {/block 'footer'} */
}
