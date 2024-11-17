<div class="tab-pane" id="epin_tab" >
	<div class="p-20">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<span class="symbol required"></span>
					<label>Enter your ePIN <font color="red">*</font></label>
					<input type="text" class="form-group" name="epin" id="epin" autocomplete="off">
					{form_error("epin")}
				</div>
			</div>
		</div>
	</div>
	<button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30" name="register" id="register" value="epin" >{lang('text_finish')}</button>
</div>