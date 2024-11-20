
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{assets_url('images/ico/apple-icon-120.png')}">
    <link rel="icon" type="image/png" href="{assets_url('images/ico/favicon.ico')}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />   
    <title> {$title} | {$site_details['name']}</title>
    
    {block name=header}
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="{assets_url()}bootv4/css/font-awesome.min.css">
    <link href="{assets_url()}bootv4/css/backoffice.min.css" rel="stylesheet" />
    <link href="{assets_url()}bootv4/css/style.css" rel="stylesheet" />
    <!-- CSS Files --> 
    {$smarty.block.child}
    <!-- END Page Level CSS--> 
    {/block}
    <link rel="shortcut icon" href="{assets_url()}images/logo/{$site_details['favicon']}" />
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
                    
                    {if log_user_id() }
                    <li class="nav-item">
                        <a href="{base_url('login')}" class="nav-link">
                            <i class="material-icons">home</i> {lang('dashboard')}
                        </a>
                    </li> 
                    {else}
                    {if current_uri() == 'login/index'}
                    <li class="nav-item row">
                       <!--  <a href="{base_url('customer-login')}" class="nav-link">
                            <i class="material-icons">fingerprint</i> Customer Login
                        </a> -->
                        <a href="{base_url('forgot')}" class="nav-link" target="new">
                            <i class="material-icons">lock_open</i> Forgot Password
                        </a>
                    </li>
                    {else}
                    <li class="nav-item {if current_uri() == 'login/index' || current_uri() == ''} active {/if} ">
                        <a href="{base_url('login')}" class="nav-link">
                            <i class="material-icons">fingerprint</i> {lang('login')}
                        </a>
                    </li>
                    {/if}
                    {/if} 
                    
                    {if current_uri() == 'forgot/index'}
                    <li class="nav-item ">
                        <a href="{base_url('forgot')}" class="nav-link" target="new">
                            <i class="material-icons">lock_open</i> Forgot Password
                        </a>
                    </li>
                    {/if}
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <div class="wrapper wrapper-full-page">

        <div class="page-header login-page header-filter" filter-color="black" style="background-image: url('{assets_url()}bootv4/img/login5.jpg'); background-size: cover; background-position: top center;"> 
            <div class="container">
                {include file="layout/flash_message.tpl"}
                {block name=body}{/block}
            </div>
        </div>
    </div> 

    {block name=footer} 
    <script src="{assets_url()}bootv4/js/core/jquery.min.js"></script>
    <script src="{assets_url()}bootv4/js/core/popper.min.js"></script>
    <script src="{assets_url()}bootv4/js/core/bootstrap-material-design.min.js"></script>
    <script src="{assets_url()}bootv4/js/plugins/perfect-scrollbar.jquery.min.js"></script> 
    <script async defer src="{assets_url()}bootv4/js/plugins/buttons.js"></script>
    <!-- Chartist JS -->
    <script src="{assets_url()}bootv4/js/plugins/chartist.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="{assets_url()}bootv4/js/plugins/bootstrap-notify.js"></script> 
    <script src="{assets_url()}bootv4/js/core/material-dashboard.min.js?v=2.1.0" type="text/javascript"></script>
    
    {$smarty.block.child} 

    <script>
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
</script>  
<script>
    $(document).ready(function() {
        md.checkFullPageBackgroundImage();
        setTimeout(function() {
            $('.card').removeClass('card-hidden');
        }, 700);
    });
</script>
{/block}
</body>

</html>