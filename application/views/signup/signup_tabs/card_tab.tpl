<div class="tab-pane" id="card_tab" >
	<span class="paymentErrors alert-danger"></span>
	<div class="form-group">
		<label>Card Number</label>
		<input type="text" name="cardNumber" size="20" autocomplete="off" id="cardNumber" class="form-control" maxlength="16" onchange="enablevalidate()" />
	</div>
	<div class="form-group">

		<label>CVC</label>
		<input type="text" name="cardCVC" size="4" autocomplete="off" id="cardCVC" class="form-control" maxlength="3"  onchange="enablevalidate()"  />

	</div>
	<div class="form-group">
		<label>Expiration (MM/YYYY)</label>
		<div class="col-lg-4">
			<input type="text" name="cardExpMonth" placeholder="MM" size="2" id="cardExpMonth" class="form-control" maxlength="2"  onchange="enablevalidate()"  />
		</div>
		<div class="col-lg-4">
			<input type="text" name="cardExpYear" placeholder="YY" size="4" id="cardExpYear" class="form-control" maxlength="4"   onchange="enablevalidate()"  />
		</div>
	</div>
	<button type="button" class="btn btn-warning btn-flat m-b-30 m-t-30" name="validate" id="validate" value="validate" onclick="myFunction()" >Validate Card</button>

	<button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30" name="register" id="registerc" value="card" style="display: none;">{lang('text_finish')}</button>


</div>


