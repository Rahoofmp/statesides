<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2024-11-17 13:52:47
  from "C:\wamp64\www\WORKS\stateside\application\views\layout\flash_message.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-23',
  'unifunc' => 'content_6739bcef9ef548_80722506',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '402fbc79ef47c71c49028bc3948c3f1b2b16e69e' => 
    array (
      0 => 'C:\\wamp64\\www\\WORKS\\stateside\\application\\views\\layout\\flash_message.tpl',
      1 => 1727327561,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6739bcef9ef548_80722506 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['box']->value && $_smarty_tpl->tpl_vars['flashdata']->value) {
if ($_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->_assignInScope('message_class', "alert alert-info alert-with-icon");
} else {
$_smarty_tpl->_assignInScope('message_class', "alert alert-warning alert-with-icon");
}?>
<style type="text/css">
	.alert.alert-with-icon {
    margin-top: 0px !important;
}
</style>
<div class="<?php echo $_smarty_tpl->tpl_vars['message_class']->value;?>
 hidden-print" data-notify="container">
	<i class="material-icons" data-notify="icon">notifications</i>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<i class="material-icons">close</i>
	</button>
	<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
	<span data-notify="message"><?php echo $_smarty_tpl->tpl_vars['flashdata']->value;?>
.</span>
</div>
<?php }
}
}
