<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2024-11-17 13:54:15
  from "C:\wamp64\www\WORKS\stateside\application\views\salesman\dashboard\index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-23',
  'unifunc' => 'content_6739bd470e75e5_61040826',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '889f435097ab9f12b9415e8d77ac39b84017c9be' => 
    array (
      0 => 'C:\\wamp64\\www\\WORKS\\stateside\\application\\views\\salesman\\dashboard\\index.tpl',
      1 => 1727327561,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6739bd470e75e5_61040826 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_modifier_replace')) require_once 'C:\\wamp64\\www\\WORKS\\stateside\\system\\libraries\\smarty\\libs\\plugins\\modifier.replace.php';
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_11201280726739bd4709fb91_85024462', "body");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_15802582836739bd470dc9e4_74476886', "footer");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/base.tpl");
}
/* {block "body"} */
class Block_11201280726739bd4709fb91_85024462 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_11201280726739bd4709fb91_85024462',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
  
<?php if (log_user_type() == "store_keeper") {?>




<div class="row">

	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Projects</p>
				<h4 class="card-title"><?php echo $_smarty_tpl->tpl_vars['project_count']->value;?>
</h4>
			</div>
			<?php if (log_user_type() == "store_keeper") {?>

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="#" > View Details</a>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-icon card-header-icon">
				<div class="card-icon">
					<i class="material-icons">cloud_done</i>
				</div>
				<p class="card-category">Pending Deliveries</p>
				<h4 class="card-title"><?php echo $_smarty_tpl->tpl_vars['pending_delivery_count']->value;?>
</h4>
			</div>
			<?php if (log_user_type() == "store_keeper") {?>

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="<?php echo base_url();?>
store_keeper/delivery/delivery-list" > View Details</a>
				</div>
			</div>
			<?php }?>
		</div>
	</div>


	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-success card-header-icon">
				<div class="card-icon">
					<i class="material-icons">message</i>
				</div>
				<p class="card-category">Send To Delivery</p>
				<h4 class="card-title"><?php echo $_smarty_tpl->tpl_vars['sendto_delivery_count']->value;?>
</h4>
			</div>
			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="<?php echo base_url();?>
store_keeper/delivery/delivery-list" > <?php echo lang('text_view_more');?>
</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="card">

			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Recent Deliveries</h4>
			</div> 

			<div class="card-body table-responsive">
				<?php if ($_smarty_tpl->tpl_vars['recent_deliveries']->value) {?> 


				<table class="table table-hover">
					<thead class="text-warning">						
						
						<th>Code</th>
						<th>Project Name</th>
						<th>Driver</th>
						<th>Status</th>
						<th>Vehicle</th>
						<th>Created On</th>					
						<th>Action</th>					
					</thead>
					<tbody> 
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['recent_deliveries']->value, 'p');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
?>
						<tr>
							
							<td><?php echo $_smarty_tpl->tpl_vars['p']->value['code'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['p']->value['project_name'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['p']->value['driver_name'];?>
</td>
							<td><?php echo smarty_modifier_replace(ucfirst($_smarty_tpl->tpl_vars['p']->value['status']),'_',' ');?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['p']->value['vehicle'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['p']->value['date_created'];?>
</td> 

							<td class="td-actions text-right ">
								<?php if ($_smarty_tpl->tpl_vars['p']->value['packages']) {?>
								<a rel="tooltip" title="QRcode" onClick="printQrCode(this)" class="btn btn-default btn-link"><i class="material-icons">grid_view</i></a>

								<?php ob_start();
echo log_user_type();
$_prefixVariable1=ob_get_clean();
$_smarty_tpl->_subTemplateRender($_prefixVariable1."/delivery/show_qrcode.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

								<?php }?>

								<?php if ($_smarty_tpl->tpl_vars['p']->value['status'] == 'pending') {?>
								<a rel="tooltip" title="Edit" href="javascript:edit_delivery_note('<?php echo $_smarty_tpl->tpl_vars['p']->value['enc_id'];?>
')" class="btn btn-success btn-link"><i class="material-icons">edit</i></i></a>
								<?php }?> 

								<a rel="tooltip" title="View" href="<?php echo base_url(log_user_type());?>
/delivery/delivery_details/<?php echo $_smarty_tpl->tpl_vars['p']->value['enc_id'];?>
" class="btn btn-info btn-link"><i class="material-icons">local_see</i></a>
							</td>   
							
						</tr>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
?>


					</tbody>
				</table>

				<div class="card-footer"> 
					<div class="stats">
						<i class="material-icons">local_offer</i><a href="<?php echo base_url();?>
store_keeper/delivery/delivery-list" > <?php echo lang('text_view_more');?>
</a>
					</div>
				</div>
				<?php } else { ?>
				<div class="alert alert-warning">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<i class="material-icons">close</i>
					</button>
					<span>
						<b> Warning - </b> No Details found
					</span>
				</div>
				<?php }?>
			</div>

		</div>
	</div>
</div>
<?php }
}
}
/* {/block "body"} */
/* {block "footer"} */
class Block_15802582836739bd470dc9e4_74476886 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_15802582836739bd470dc9e4_74476886',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
 type="text/javascript">

	function edit_delivery_note(id)
	{
		swal({
			title:'<?php echo lang('text_are_you_sure');?>
',
			text: "You will not recover",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: '<?php echo lang('text_yes_update_it');?>
',
			cancelButtonText: '<?php echo lang('text_no_cancel_please');?>
',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				document.location.href = '<?php echo base_url(log_user_type());?>
' + "/delivery/add-delivery-items/"+id; 
			} else {
				swal('<?php echo lang('text_cancelled');?>
','<?php echo lang('your_content_safe');?>
', "error");
			}
		});
	} 
	function printQrCode(ele) {
		var windowUrl = 'about:blank'
		var uniqueName = new Date();
		var windowName = 'Print' + uniqueName.getTime();

		var myPrintContent = $(ele).parent().find('.printdiv')[0];
		var myPrintWindow = window.open(windowUrl, windowName, 'left=300,top=100,width=400,height=400');
		myPrintWindow.document.write(myPrintContent.innerHTML);
		myPrintWindow.document.close();
		myPrintWindow.focus();
		myPrintWindow.print();
		myPrintWindow.close();    
		return false;
	}
<?php echo '</script'; ?>
>

<?php
}
}
/* {/block "footer"} */
}
