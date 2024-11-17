<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2024-11-17 13:52:47
  from "C:\wamp64\www\WORKS\stateside\application\views\login\index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-23',
  'unifunc' => 'content_6739bcef701ed0_41210089',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c58c29d6192b66f4f4dea56531607a4931c5ae2b' => 
    array (
      0 => 'C:\\wamp64\\www\\WORKS\\stateside\\application\\views\\login\\index.tpl',
      1 => 1727327561,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6739bcef701ed0_41210089 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_10299889606739bcef6f4115_31001213', "body");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "login/layout.tpl");
}
/* {block "body"} */
class Block_10299889606739bcef6f4115_31001213 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_10299889606739bcef6f4115_31001213',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
 

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
        <?php echo form_open('','class="form form-horizontal form-simple"');?>

        <div class="card card-login card-hidden">
            <div class="card-header card-header-rose text-center">
                <h4 class="card-title"><?php echo lang('button_login');?>
</h4>
            </div>
            <div class="card-body ">
                <span class="bmd-form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">account_box</i>
                            </span>
                        </div>
                        <input type="text" class="form-control form-control-lg required" id="user-name" name="user_name" placeholder="<?php echo lang('username');?>
" required autocomplete="off">
                    </div>
                    <?php echo form_error('user_name');?>

                </span>
                <span class="bmd-form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">lock</i>
                            </span>
                        </div>
                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="<?php echo lang('password');?>
" required >
                    </div>
                    <p class="text-right"><a href="<?php echo base_url('forgot');?>
"> Forgot password</a></p>
                    <?php echo form_error('password');?>
 
                </span>
            </div>
            <div class="card-footer justify-content-center"> 
                <button type="submit" class="btn btn-rose" name="login" value="login"><i class="fa fa-unlock"></i> <?php echo lang('button_login');?>
</button>
            </div>
   
        </div>
        <?php echo form_close();?>

    </div>
</div>

<?php
}
}
/* {/block "body"} */
}
