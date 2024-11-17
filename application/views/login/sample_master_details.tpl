{extends file="login/layout.tpl"}

{block name="body"} 

<div class="row">
    <div class="col-sm-6 m-auto">
        {if $info}
        <div class="card card-profile">
            <div class="card-avatar">
                <a href="javascript:;">
                    <img class="img" src="../../assets/images/sample/{$info['item_images'][0]->image}">
                </a>
            </div>
            <div class="card-body">
                <h6 class="card-category text-gray">{$info['code']}</h6>
                <h4 class="card-title">{$info['name']}</h4>
                <p class="card-description">
                    {$info['category_name']}
                </p>
                <table class="table table-hover"> 
                    <tbody>
                        <tr> 
                            <td><strong>Brand</strong></td>
                            <td>{$info['brand']}</td> 
                        </tr> 
                        <tr> 
                            <td><strong>Origin</strong></td>
                            <td>{$info['origin']}</td> 
                        </tr> 
                        <tr> 
                            <td><strong>Paint code</strong></td>
                            <td>{$info['paint_code']}</td> 
                        </tr>  
                        <tr> 
                            <td><strong>Type of material</strong></td>
                            <td>{$info['type']}</td> 
                        </tr> 
                        <tr> 
                            <td><strong>Size</strong></td>
                            <td>{$info['size']}</td> 
                        </tr> 
                        <tr> 
                            <td><strong>Grade</strong></td>
                            <td>{$info['grade']}</td> 
                        </tr> 
                        <tr> 
                            <td><strong>Price</strong></td>
                            <td>{$info['lprice']}</td> 
                        </tr> 
                    </tbody>
                </table>
             </div>
        </div>
        {else}
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="material-icons">close</i>
            </button>
            <span>
                <b> Warning - </b> Details found</span>
            </div>
        </div>
        {/if}
    </div>
</div>

{/block}