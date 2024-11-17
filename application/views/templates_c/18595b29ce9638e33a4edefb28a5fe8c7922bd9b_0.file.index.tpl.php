<?php
/* Smarty version {Smarty::SMARTY_VERSION}, created on 2024-11-17 13:53:13
  from "C:\wamp64\www\WORKS\stateside\application\views\admin\dashboard\index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32-dev-23',
  'unifunc' => 'content_6739bd09b48122_88343642',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '18595b29ce9638e33a4edefb28a5fe8c7922bd9b' => 
    array (
      0 => 'C:\\wamp64\\www\\WORKS\\stateside\\application\\views\\admin\\dashboard\\index.tpl',
      1 => 1727327558,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6739bd09b48122_88343642 (Smarty_Internal_Template $_smarty_tpl) {
if (!is_callable('smarty_function_counter')) require_once 'C:\\wamp64\\www\\WORKS\\stateside\\system\\libraries\\smarty\\libs\\plugins\\function.counter.php';
if (!is_callable('smarty_modifier_replace')) require_once 'C:\\wamp64\\www\\WORKS\\stateside\\system\\libraries\\smarty\\libs\\plugins\\modifier.replace.php';
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3523946046739bd09adb196_33065824', 'header');
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_8656559696739bd09adf1c2_19010223', "body");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_6026023936739bd09b39370_89403433', "footer");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/base.tpl");
}
/* {block 'header'} */
class Block_3523946046739bd09adb196_33065824 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_3523946046739bd09adb196_33065824',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<link rel="stylesheet" type="text/css" href="<?php echo assets_url('plugins/sweetalert/lib/sweet-alert.css');?>
">
<?php
}
}
/* {/block 'header'} */
/* {block "body"} */
class Block_8656559696739bd09adf1c2_19010223 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_8656559696739bd09adf1c2_19010223',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
  
<?php if (log_user_type() == "admin") {?>

<div class="row">


	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-warning card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Projects</p> <h4 class="card-title"><?php echo $_smarty_tpl->tpl_vars['project_count']->value;?>
</h4>
			</div> 
			
		</div>
	</div>

	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-success card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Job Order</p>
				<h4 class="card-title"><?php echo $_smarty_tpl->tpl_vars['job_order_count']->value;?>
</h4>
			</div>
			<?php if (log_user_type() == "admin") {?>

			
			<?php }?>
		</div>
	</div>

	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-danger card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Pending Job Orders</p>
				<h4 class="card-title"><?php echo $_smarty_tpl->tpl_vars['pending_job_order']->value;?>
</h4>
			</div>
			
		</div>
	</div>
</div>

<div class="row">

	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Delivery Notes</p>
				<h4 class="card-title"><?php echo $_smarty_tpl->tpl_vars['count_delivery_notes']->value;?>
</h4>
			</div>
			<?php if (log_user_type() == "admin") {?>

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="<?php echo base_url();?>
admin/project/project-list" > View Details</a>
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
			<?php if (log_user_type() == "admin") {?>

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="<?php echo base_url();?>
admin/delivery/delivery-list" > View Details</a>
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
admin/delivery/delivery-list" > <?php echo lang('text_view_more');?>
</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Recent Delivery Notes(Latest 10)</h4>
			</div>
			<div class="card-body">
				<?php if ($_smarty_tpl->tpl_vars['details']->value) {?>
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Code</th>
								<th>Project Name</th>
								<th>Driver</th>
								<th>Vehicle</th>
								<th>Status</th>
								<th>Created On</th>
								<th>Action</th>
								
							</tr>
						</thead>
						<tbody>

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['details']->value, 'v');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['v']->value) {
?> 

							<tr>
								<td ><?php echo smarty_function_counter(array(),$_smarty_tpl);?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['v']->value['code'];?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['v']->value['project_name'];?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['v']->value['driver_name'];?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['v']->value['vehicle'];?>
</td>
								<td><?php echo smarty_modifier_replace(ucfirst($_smarty_tpl->tpl_vars['v']->value['status']),'_',' ');?>
</td>
								<td><?php echo $_smarty_tpl->tpl_vars['v']->value['date_created'];?>
</td>   
								<td class="td-actions text-right ">
									<?php if ($_smarty_tpl->tpl_vars['v']->value['status'] == 'pending') {?>
									<a rel="tooltip" title="Edit" href="javascript:edit_delivery_note('<?php echo $_smarty_tpl->tpl_vars['v']->value['enc_id'];?>
')" class="btn btn-success btn-link"><i class="material-icons">edit</i></i></a>
									<?php }?>
									<a rel="tooltip" title="View" href="<?php echo base_url();?>
admin/delivery/delivery_details/<?php echo $_smarty_tpl->tpl_vars['v']->value['enc_id'];?>
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
				</div>
				<div class="card-footer"> 
					<div class="stats">
						<i class="material-icons">local_offer</i><a href="<?php echo base_url();
echo log_user_type();?>
/delivery/delivery-list" > <?php echo lang('text_view_more');?>
</a>
					</div>
				</div>
				<?php } else { ?>
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Delivery 	Details Found</i>
						</h4>
					</p>
				</div>
				<?php }?>
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
				<h4 class="card-title">Recent projects</h4>
			</div> 

			<div class="card-body table-responsive">
				<?php if ($_smarty_tpl->tpl_vars['recent_projects']->value) {?> 


				<table class="table table-hover">
					<thead class="text-warning">						
						<th>Sl.No.</th>
						<th>Name</th>
						<th>Cust. Name</th> 
						<th>Contact</th>
						
						<th>Date</th>
						<th>Status</th>					
						<th>Action</th>							
					</thead>
					<tbody> 
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['recent_projects']->value, 'p', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['p']->value) {
?>
						<tr>

							<td><?php echo $_smarty_tpl->tpl_vars['key']->value+1;?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['p']->value['project_name'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['p']->value['cus_name'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['p']->value['customer_mobile'];?>

								<span class="clearfix"></span>
								<?php echo $_smarty_tpl->tpl_vars['p']->value['customer_email'];?>

							</td>
							<td><?php echo $_smarty_tpl->tpl_vars['p']->value['date'];?>
</td>
							<td><?php echo smarty_modifier_replace(ucfirst($_smarty_tpl->tpl_vars['p']->value['status']),'_',' ');?>
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
								<a rel="tooltip" title="Edit" href="javascript:edit_project('<?php echo $_smarty_tpl->tpl_vars['p']->value['enc_id'];?>
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
						<i class="material-icons">local_offer</i><a href="<?php echo base_url();
echo log_user_type();?>
/project/project-list" > <?php echo lang('text_view_more');?>
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
class Block_6026023936739bd09b39370_89403433 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_6026023936739bd09b39370_89403433',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
 src="<?php echo assets_url('plugins/sweetalert/lib/sweet-alert.min.js');?>
"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 type="text/javascript">
	function edit_project(id)
	{
		swal({
			title: 'Are you sure?',
			text: 'do you want to edit ',
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: 'edit',
			cancelButtonText: 'Cancel Please',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				document.location.href = "<?php echo base_url();
echo log_user_type();?>
/project/add_project/"+id; 
			} else {
				swal('<?php echo lang("text_cancelled");?>
','<?php echo lang("text_cancelled");?>
', "error");
			}
		});
	}
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
				document.location.href = '<?php echo base_url();?>
' + "admin/delivery/add-delivery-items/"+id; 
			} else {
				swal('<?php echo lang('text_cancelled');?>
','<?php echo lang('your_content_safe');?>
', "error");
			}
		});
	}
<?php echo '</script'; ?>
>

<?php
}
}
/* {/block "footer"} */
}
