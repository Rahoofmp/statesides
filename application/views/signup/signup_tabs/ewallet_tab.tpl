<div class="tab-pane active" id="ewallet_tab" >
	<div class="p-20">
		<div class="form-group">
			<label>{lang("wallet_username")}  <font color="red">*</font></label>
			<input type="text" class="form-control" id="user_name_wallet" name="user_name_wallet" value="{set_value('user_name_wallet')}" autocomplete="off">
			<span id="error_wallet_username"></span>
			{form_error("user_name_wallet")}
		</div>
		<div class="form-group">
			<label>{lang("transaction_password")}  <font color="red">*</font></label>
			<input type="password" class="form-control" id="tran_pass_ewallet" name="tran_pass_ewallet" autocomplete="off" >
			{form_error("tran_pass_ewallet")}
		</div>
		
		<button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30" name="register" id="register" value="ewallet" >{lang('text_finish')}</button>

	</div>
</div>