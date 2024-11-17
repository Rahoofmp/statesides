<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2024-11-17 13:53:14
  from "C:\wamp64\www\WORKS\stateside\application\views\layout\base.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-23',
  'unifunc' => 'content_6739bd0a035ba1_43264965',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b119a3cef9cd6e22a6557c947afb346b26cd1c70' => 
    array (
      0 => 'C:\\wamp64\\www\\WORKS\\stateside\\application\\views\\layout\\base.tpl',
      1 => 1731775568,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layout/menu.tpl' => 1,
    'file:layout/flash_message.tpl' => 1,
  ),
),false)) {
function content_6739bd0a035ba1_43264965 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_date_format')) require_once 'C:\\wamp64\\www\\WORKS\\stateside\\system\\libraries\\smarty\\libs\\plugins\\modifier.date_format.php';
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />   
    <title> <?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 | <?php echo $_smarty_tpl->tpl_vars['site_details']->value['name'];?>
</title>

    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_9965995746739bd0a00b8f4_79692203', 'header');
?>

    <link rel="shortcut icon" href="<?php echo assets_url();?>
images/logo/favicon.png" />
</head>

<body class="">

    <input type="hidden" id="rootPath" value="<?php echo base_url();?>
">
    <input type="hidden" id="logType" value="<?php echo log_user_type();?>
">
    <div class="wrapper ">
        <div class="sidebar" data-color="rose" data-background-color="black" data-image="<?php echo assets_url();?>
support/img/sidebar-1.jpg">
            <div class="logo">
                <a href="<?php echo base_url();?>
" class="simple-text logo-normal">
                    <img src="<?php echo assets_url();?>
images/logo/logo.png" style="margin-left: 45px;max-width: 118px;"  />
                </a>
            </div>
            <?php $_smarty_tpl->_subTemplateRender("file:layout/menu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    

        </div>

        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top hidden-print">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-minimize">
                            <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                                <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
                                <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
                            </button>
                        </div>
                        <a class="navbar-brand" href="#pablo"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end">

                        <ul class="navbar-nav"> 
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">notifications</i>
                                    <span class="notification"><?php echo $_smarty_tpl->tpl_vars['unread_mail']->value;?>
</span>
                                    <p class="d-lg-none d-md-block">
                                        Some Actions
                                    </p>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="<?php echo base_url();
echo log_user_type();?>
/mail/inbox">Internal Mails</a>
                                      
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">person</i>
                                    <p class="d-lg-none d-md-block">
                                        Account
                                    </p>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                                    <a class="dropdown-item" href="<?php echo base_url(log_user_type());?>
/member/profile">Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?php echo base_url();?>
/logout">Log out</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <?php $_smarty_tpl->_subTemplateRender("file:layout/flash_message.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

                    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15582824176739bd0a01d1b2_47258157', 'body');
?>

                </div>
            </div>
            <footer class="footer hidden-print">
                <div class="container-fluid">

                    <div class="copyright ">
                        <?php echo smarty_modifier_date_format(time(),"%Y");?>
 &copy; 
                        <a href="<?php echo base_url();?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['site_details']->value['name'];?>
</a>, 
                        Developed By: 
                        <a href="<?php echo base_url();?>
" target="_blank">TechMagic</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
        

        <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15553238836739bd0a024e59_23617844', 'footer');
?>

</body>


</html><?php }
/* {block 'header'} */
class Block_9965995746739bd0a00b8f4_79692203 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_9965995746739bd0a00b8f4_79692203',
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
class Block_15582824176739bd0a01d1b2_47258157 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_15582824176739bd0a01d1b2_47258157',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'body'} */
/* {block 'footer'} */
class Block_15553238836739bd0a024e59_23617844 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_15553238836739bd0a024e59_23617844',
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
 src="<?php echo assets_url();?>
bootv4/js/plugins/jquery.validate.min.js"><?php echo '</script'; ?>
>

        <?php echo '<script'; ?>
 src="<?php echo assets_url();?>
bootv4/js/core/material-dashboard.min.js?v=2.1.0" type="text/javascript"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo base_url();?>
jsloader/lang_common.js" ><?php echo '</script'; ?>
>

        <?php echo '<script'; ?>
>
            function setFormValidation(id) {
              $(id).validate({
                highlight: function(element) {
                  $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                  $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
              },
              success: function(element) {
                  $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                  $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
              },
              errorPlacement: function(error, element) {
                  $(element).closest('.form-group').append(error);
              },
          });
          }
      <?php echo '</script'; ?>
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
<?php
}
}
/* {/block 'footer'} */
}
