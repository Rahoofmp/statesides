{extends file="login/layout.tpl"}

{block body}
<div class="row">
<div class="col-md-12">
    <div class="card"> 
        <div class="card-header card-header-rose card-header-text">
            <div class="card-icon">
                <i class="material-icons">library_books</i>
            </div>
            <h4 class="card-title">Confirm Job Order</h4>
        </div>
        <div class="card-body">
            <div id="accordion" role="tablist">

                <div class="card-collapse">
                    <div class="card-header" role="tab" id="headingOne">
                        <h5 class="mb-0">
                            <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed">
                                JOB ORDER
                                <i class="material-icons">keyboard_arrow_down</i>
                            </a>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="input-group form-control-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="material-icons">grading</i>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            <label class="bmd-label-floating">
                                                                Job order ID
                                                            </label>
                                                            <input type="number" class="form-control" id="order_id" name="order_id"  value="{$jobs['order_id']}" disabled="" autocomplete="Off">
                                                            {form_error("order_id")}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group form-control-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="material-icons">grading</i>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            <label class="bmd-label-floating">
                                                                Job order name
                                                            </label>
                                                            <input type="text" class="form-control" id="name" name="name" value="{$jobs['name']}"  disabled="" autocomplete="Off">
                                                            {form_error("name")}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group form-control-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="material-icons">person</i>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            <label class="bmd-label-floating">
                                                                Customer
                                                            </label>
                                                            <input type="text" class="form-control" id="name" name="name" value="{$jobs['customer_name']}"  disabled="" autocomplete="Off"> 
                                                            {form_error("customer_name")}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="input-group form-control-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="material-icons">date_range</i>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            <label class="bmd-label-floating"> Delivery requested date </label>

                                                            <input type="text" id="requested_date" class="form-control datepicker" name="requested_date" value="{$jobs['requested_date']}"disabled autocomplete="Off">
                                                            {form_error("requested_date")} 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="input-group form-control-lg">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="material-icons">person</i>
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-10">
                                                        <div class="form-group">
                                                            <label class="bmd-label-floating">
                                                                Project
                                                            </label>


                                                            <input type="text" class="form-control" id="name" name="name" value="{$jobs['project_name']}"  disabled="" autocomplete="Off">



                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {form_open('','id="file_form" name="file_form" class="form-add-project ValidateForm" enctype="multipart/form-data"')}
                <div class="card-collapse">
                    <div class="card-header" role="tab" id="headingThree">
                        <h5 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                DEPARTMENT JOBS

                                <i class="material-icons">keyboard_arrow_down</i>
                            </a>
                        </h5>
                    </div> 
                    <div id="collapseThree" class="collapse show" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="row">
                            <div class="col-md-12" > 

                                <div class="table-responsive">
                                    <table class="table table-hover" id="sample-table-1">
                                        <thead class="bg-light text-warning">
                                            <tr>
                                                <th>Department</th>
                                                <th>Short Description</th>
                                                <th>Order Description</th> 
                                                <th>Estimated <br>Working Hours</th> 

                                            </tr>
                                        </thead> 
                                        <tbody>
                                            {foreach $jobs['department'] as $d}
                                            <tr>
                                                <td>{counter}. {$d.department_name}</td>
                                                <td>{$d.short_description}</td>
                                                <td>{$d.order_description}</td>
                                                <td>{$d.estimated_working_hrs}</td>

                                            </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row col-md-12" align="center">
                                {if $jobs['admin_status']=='pending'}
                                <!-- </div> -->
                                <button type="submit" name="submit" class="form-control col-md-3 btn btn-danger pull-right" value="reject">Reject</button>
                                <button type="submit" name="submit" class="form-control col-md-3 btn btn-success pull-right" value="confirm">Confirm</button>
                                {elseif $jobs['admin_status']=='rejected'}{$class="badge-alert"}{else}{$class="badge-success"}
                                Admin Verification Status: <span class="badge badge-pill {$class}">
                                    {$jobs['admin_status']}
                                </span>

                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
                {form_close()}
            </div>
        </div>
    </div>
</div>
</div>
</div>


{/block}