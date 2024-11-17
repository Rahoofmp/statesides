<div class="sidebar-wrapper">
    <div class="user">
        <div class="photo">
            <img src="{assets_url()}images/profile_pic/{$profile_pic}" />
        </div>
        <div class="user-info">
            <a data-toggle="collapse" href="#" class="username">
                <span>
                    {log_user_name()} 
                </span>
            </a> 
        </div>
    </div>
    <ul class="nav">  
        {assign var=sub_menu_count value=0}
        {foreach from=$SIDE_MENU item=v key=k}
        {$sub_menu_count=count($v.submenu)}

        
        <li class="nav-item {if $v.is_selected}active{/if} ">
            <a class="nav-link" {if $sub_menu_count} data-toggle="collapse" href="#{$v.id}" {else} href="{$v.link}" {/if} >
                <i class="material-icons">{$v.icon}</i>
                <p >{$v.text}
                    {if $sub_menu_count} <b class="caret"></b> {/if}
                </p>
            </a>
            {if $sub_menu_count}
            <div class="collapse {if $v.is_selected}show{/if}" id="{$v.id}">
                <ul class="nav">
                    {foreach from=$v.submenu item=i}
                    <li class="nav-item {if $i.is_selected}active{/if}">
                        <a class="nav-link " href="{$i.link}">
                            <span class="sidebar-mini"> {$i.text|substr:0:1}  </span>
                            <span class="sidebar-normal"> {$i.text} </span>
                        </a>
                    </li>
                    {/foreach}
                </ul>
            </div>
            {/if}
        </li>
        {/foreach} 
    </ul>
    <br>
    <br>
    <br>
</div>