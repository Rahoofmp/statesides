{extends file="layout/base.tpl"}

{block header} 

<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" />  
<link href="{assets_url()}plugins/DataTables/datatables.min.css" rel="stylesheet" />


{/block}

{block body}

<div class="row">
	<div class="col-md-12">
		<div class="card"> 


			<div class="card-body">
				
				{form_open('','')}
				<div class="card-collapse">

					
					<div class="row">
						
						{* <div class="col-lg-6">
							<div class="input-group form-control-lg">
								
								<div class="col-sm-10">
									<div class="form-group">
										
										<select id="department_id" name="department_id" class=" form-control" required=""value='{$select_arr['department_name']}'>
									
									{foreach $departments as $d}
									<option value="{$d.id}"  {if in_array($d.id, $dept_ids)} disabled=""{/if}>{$d.name}</option>
									{/foreach}


								</select> 
										{form_error("department_name")}
									</div>
								</div>
							</div>
						</div> *}
						<div class="col-lg-6">
							<div class="input-group form-control-lg">
								
								<div class="col-sm-10">
									<div class="form-group">
										<label class="bmd-label-floating">
											Short Description
										</label>
										<input type="text" class="form-control" id="short_description" name="short_description" value="{$select_arr['short_description']}"required="true" autocomplete="Off">
										{form_error("short_description")}
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="input-group form-control-lg">
								
								<div class="col-sm-10">
									<div class="form-group">
										<label class="bmd-label-floating">
											Order Description
										</label>
										<input type="text" class="form-control" id="order_description" name="order_description" value="{$select_arr['order_description']}" required="true" autocomplete="Off">
										{form_error("order_description")}
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="input-group form-control-lg">
								
								<div class="col-sm-10">
									<div class="form-group">
										<label class="bmd-label-floating">
											Estimated Working Hours
										</label>
										<input type="text" class="form-control" id="estimated_working_hrs" name="estimated_working_hrs"value="{$select_arr['estimated_working_hrs']} Hrs"required="true" autocomplete="Off">
										{form_error("estimated_working_hrs")}
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer text-right">
							<button class="btn btn-primary pull-right" type="submit" id="update_dept_job" name="update_dept_job" value="update_dept_job">
								Update <i class="fa fa-arrow-circle-right"></i>
							</button>
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
<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>

<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>

<script src="{assets_url()}plugins/DataTables/datatables.min.js"></script>


<script src="{assets_url('js/ui-notifications.js')}"></script>





{/block}
