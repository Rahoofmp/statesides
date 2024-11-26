<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />

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
    <link rel="shortcut icon" href="{assets_url()}images/logo/favicon.png" />

    <style type="text/css">
        .main-panel {
    position: relative;
    float: right;
    width: calc(100% - 20px);
    transition: 0.33s, cubic-bezier(0.685, 0.0473, 0.346, 1);
}
.main-panel > .content {
    margin-top: 30px;
    padding: 30px 15px;
    min-height: calc(100vh - 123px);
}
.main-panel > .footer {
    border-top: 1px solid #e7e7e7;
}
.main-panel > .navbar {
    margin-bottom: 0;
}
.main-panel .header {
    margin-bottom: 30px;
}
.main-panel .header .title {
    margin-top: 10px;
    margin-bottom: 10px;
}
.perfect-scrollbar-on .main-panel,
.perfect-scrollbar-on .sidebar {
    height: 100%;
    max-height: 100%;
}
    </style>
</head>

<body class="">

    <input type="hidden" id="rootPath" value="{base_url()}">
    <input type="hidden" id="logType" value="{log_user_type()}">
    <div class="wrapper ">
      <!--   <div class="sidebar" data-color="rose" data-background-color="black" data-image="{assets_url()}support/img/sidebar-1.jpg">
            <div class="logo">
                <a href="{base_url()}" class="simple-text logo-normal">
                    <img src="{assets_url()}images/logo/logo.png" style="margin-left: 45px;max-width: 118px;"  />
                </a>
            </div>
            {include file="layout/menu.tpl"}    

        </div> -->

        <div class="main-panel">
            <!-- Navbar -->




            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    {include file="layout/flash_message.tpl"}
                    {block name=body}{/block}
                </div>
            </div>
            <footer class="footer hidden-print text-center">
                <div class="container-fluid">

                   <!--  <div class="copyright ">
                        {$smarty.now|date_format:"%Y"} &copy; 
                        <a href="{base_url()}" target="_blank">Statesside Group</a>, 
                        Developed By: 
                        <a href="https://techmagictechnologies.com/" target="_blank">TechMagic Technologies</a>
                    </div> -->
                </div>
            </footer>
        </div>
    </div>
    {*  <div class="fixed-plugin hidden-print">
        <div class="dropdown show-dropdown">
            <a href="#" data-toggle="dropdown">
                <i class="fa fa-cog fa-2x"> </i>
            </a>
            <ul class="dropdown-menu">
                <li class="header-title"> Sidebar Filters</li>
                <li class="adjustments-line">
                    <a href="javascript:void(0)" class="switch-trigger active-color">
                        <div class="badge-colors ml-auto mr-auto">
                            <span class="badge filter badge-purple" data-color="purple"></span>
                            <span class="badge filter badge-azure" data-color="azure"></span>
                            <span class="badge filter badge-green" data-color="green"></span>
                            <span class="badge filter badge-warning" data-color="orange"></span>
                            <span class="badge filter badge-danger" data-color="danger"></span>
                            <span class="badge filter badge-rose active" data-color="rose"></span>
                        </div>
                        <div class="clearfix"></div>
                    </a>
                </li>
                <li class="header-title">Sidebar Background</li>
                <li class="adjustments-line">
                    <a href="javascript:void(0)" class="switch-trigger background-color">
                        <div class="ml-auto mr-auto">
                            <span class="badge filter badge-black active" data-background-color="black"></span>
                            <span class="badge filter badge-white" data-background-color="white"></span>
                            <span class="badge filter badge-red" data-background-color="red"></span>
                        </div>
                        <div class="clearfix"></div>
                    </a>
                </li>
                <li class="adjustments-line">
                    <a href="javascript:void(0)" class="switch-trigger">
                        <p>Sidebar Mini</p>
                        <label class="ml-auto">
                            <div class="togglebutton switch-sidebar-mini">
                                <label>
                                    <input type="checkbox">
                                    <span class="toggle"></span>
                                </label>
                            </div>
                        </label>
                        <div class="clearfix"></div>
                    </a>
                </li>
                <li class="adjustments-line">
                    <a href="javascript:void(0)" class="switch-trigger">
                        <p>Sidebar Images</p>
                        <label class="switch-mini ml-auto">
                            <div class="togglebutton switch-sidebar-image">
                                <label>
                                    <input type="checkbox" checked="">
                                    <span class="toggle"></span>
                                </label>
                            </div>
                        </label>
                        <div class="clearfix"></div>
                    </a>
                </li> 
            </ul>
        </div>
    </div> *} 

    {block name=footer} 
    <script src="{assets_url()}bootv4/js/core/jquery.min.js"></script>
    <script src="{assets_url()}bootv4/js/core/popper.min.js"></script>
    <script src="{assets_url()}bootv4/js/core/bootstrap-material-design.min.js"></script>
    <script src="{assets_url()}bootv4/js/plugins/perfect-scrollbar.jquery.min.js"></script> 

    <script src="{assets_url()}bootv4/js/plugins/jquery.validate.min.js"></script>

    <script src="{assets_url()}bootv4/js/core/material-dashboard.min.js?v=2.1.0" type="text/javascript"></script>
    <script type="text/javascript" src="{base_url()}jsloader/lang_common.js" ></script>

    <script>
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
  </script>

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
{/block}
</body>


</html>