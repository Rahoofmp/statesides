{if $box && $flashdata}
{if $type }
{assign var="message_class" value="alert alert-info alert-with-icon"}
{else}
{assign var="message_class" value="alert alert-warning alert-with-icon"}
{/if}
<style type="text/css">
	.alert.alert-with-icon {
    margin-top: 0px !important;
}
</style>
<div class="{$message_class} hidden-print" data-notify="container">
	<i class="material-icons" data-notify="icon">notifications</i>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<i class="material-icons">close</i>
	</button>
	<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
	<span data-notify="message">{$flashdata}.</span>
</div>
{/if}