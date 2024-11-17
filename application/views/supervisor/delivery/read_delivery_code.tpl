{extends file="layout/base.tpl"}



{block body} 

<div class="row "> 
	<div class="col-sm-12 hidden-print"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body"> 
					{form_open('','')}
					<div class="form-body">
						<div class="row"> 
							<div class="col-md-5">
								<div class="form-group">
									<label for="issueinput3">Enter/Read Delivery code</label>
									<input type="text"  class="form-control" name="delivery_code"  id="delivery_code" value="{set_value('delivery_code')}">
								</div>
							</div> 

							<div class="col-md-4"> 
								<button type="submit" class="btn btn-primary" name="search" value="search">
									<i class="fa fa-search"></i> {lang('button_search')}
								</button> 
							</div>
						</div>
					</div>
					{form_close()}
				</div>
			</div>
		</div> 
	</div> 
</div> 

{/block}
{block footer} 

<script type="text/javascript">
	$('#delivery_code').focus(); 
</script>
{/block}