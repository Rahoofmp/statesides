{extends file="layout/base.tpl"}
{block header} 

{/block}
{block body} 
<style type="text/css">
    .my-group .form-control{
        width:50%;
    } 
    @media print {
        table tr td p,table tr td { 
            font-size:12px;
            padding: 0px;
            margin: 0px;
            margin-top:5px; 
        }

    }
    table.dataTable thead > tr > th.sorting{
        text-align: left !important;
    </style>
    <div class="row "> 
        <div class="col-sm-12 hidden-print"> 
            <div class="card"> 
                <div class="card-content">
                    <div class="card-header"> 

                        <h3 class="text-center" style="margin-top: 20px;">
                            <b>Price Code</b></h3>
                        </div>
                    </div>

                    <div class="card-body" align="center">

                      <h4>  <table class="table table-bordered text-center">
                          <tr>
                            <th>C&nbsp;</th>
                            <th>O&nbsp;</th>                         
                            <th>R&nbsp;</th>                            
                            <th>N&nbsp;</th>                            
                            <th>F&nbsp;</th>                            
                            <th>L&nbsp;</th>                           
                            <th>A&nbsp;</th>                            
                            <th>K&nbsp;</th>                           
                            <th>E&nbsp;</th>                            
                            <th>S&nbsp;</th>                           
                            <th>X&nbsp;</th>
                        </tr>
                        <tr>
                            <td>0</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                            <td>8</td>
                            <td>9</td>
                            <td>.</td>
                        </tr>
                    </h4>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div> 
</div> 
</div> 

{/block}