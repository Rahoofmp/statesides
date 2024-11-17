<div class="tab-pane  p-20" id="payeer" role="tabpanel">
	{form_open("https://payeer.com/merchant/",'id="payeer_form" name="btc_form" class="form-login"')}
	<div class="col-sm-12">

		{* <form method="post" action="https://payeer.com/merchant/"> *}
		<input type="hidden" name="m_shop" value="{$m_shop}">
		<input type="hidden" name="m_orderid" value="{$m_orderid}">
		<input type="hidden" name="m_amount" value="{$m_amount}">
		<input type="hidden" name="m_curr" value="{$m_curr}">
		<input type="hidden" name="m_desc" value="{$m_desc}">
		<input type="hidden" name="m_sign" value="{$sign}">

		{* <input type="hidden" name="form[ps]" value="2609">
		<input type="hidden" name="form[curr[2609]]" value="USD">
		<input type="hidden" name="m_params" value="<?=$m_params?>">
		<input type="hidden" name="m_cipher_method" value="AES-256-CBC"> *}
		
		{* </form> *}
	</div>

	<div class="p-20"> 
		<button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30" name="register" id="register" value="payeer" >{lang('text_finish')}</button> 
	</div>
	{form_close()}
</div>