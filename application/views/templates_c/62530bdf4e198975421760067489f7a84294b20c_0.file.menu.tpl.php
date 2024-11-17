<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2024-11-17 13:53:14
  from "C:\wamp64\www\WORKS\stateside\application\views\layout\menu.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-23',
  'unifunc' => 'content_6739bd0a186aa3_86919264',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '62530bdf4e198975421760067489f7a84294b20c' => 
    array (
      0 => 'C:\\wamp64\\www\\WORKS\\stateside\\application\\views\\layout\\menu.tpl',
      1 => 1731774020,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6739bd0a186aa3_86919264 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class="sidebar-wrapper">
    <div class="user">
        <div class="photo">
            <img src="<?php echo assets_url();?>
images/profile_pic/<?php echo $_smarty_tpl->tpl_vars['profile_pic']->value;?>
" />
        </div>
        <div class="user-info">
            <a data-toggle="collapse" href="#" class="username">
                <span>
                    <?php echo log_user_name();?>
 
                </span>
            </a> 
        </div>
    </div>
    <ul class="nav">  
        <?php $_smarty_tpl->_assignInScope('sub_menu_count', 0);
?>
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['SIDE_MENU']->value, 'v', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
?>
        <?php $_smarty_tpl->_assignInScope('sub_menu_count', count($_smarty_tpl->tpl_vars['v']->value['submenu']));
?>

        
        <li class="nav-item <?php if ($_smarty_tpl->tpl_vars['v']->value['is_selected']) {?>active<?php }?> ">
            <a class="nav-link" <?php if ($_smarty_tpl->tpl_vars['sub_menu_count']->value) {?> data-toggle="collapse" href="#<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" <?php } else { ?> href="<?php echo $_smarty_tpl->tpl_vars['v']->value['link'];?>
" <?php }?> >
                <i class="material-icons"><?php echo $_smarty_tpl->tpl_vars['v']->value['icon'];?>
</i>
                <p ><?php echo $_smarty_tpl->tpl_vars['v']->value['text'];?>

                    <?php if ($_smarty_tpl->tpl_vars['sub_menu_count']->value) {?> <b class="caret"></b> <?php }?>
                </p>
            </a>
            <?php if ($_smarty_tpl->tpl_vars['sub_menu_count']->value) {?>
            <div class="collapse <?php if ($_smarty_tpl->tpl_vars['v']->value['is_selected']) {?>show<?php }?>" id="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
">
                <ul class="nav">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['v']->value['submenu'], 'i');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value) {
?>
                    <li class="nav-item <?php if ($_smarty_tpl->tpl_vars['i']->value['is_selected']) {?>active<?php }?>">
                        <a class="nav-link " href="<?php echo $_smarty_tpl->tpl_vars['i']->value['link'];?>
">
                            <span class="sidebar-mini"> <?php echo substr($_smarty_tpl->tpl_vars['i']->value['text'],0,1);?>
  </span>
                            <span class="sidebar-normal"> <?php echo $_smarty_tpl->tpl_vars['i']->value['text'];?>
 </span>
                        </a>
                    </li>
                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>

                </ul>
            </div>
            <?php }?>
        </li>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>
 
    </ul>
    <br>
    <br>
    <br>
</div><?php }
}
