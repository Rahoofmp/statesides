
{form_open('admin/sales/update_sales','id="item_add"')}

<div class="form-group">
	<label for="code" class="bmd-label-floating"> Code</label>                   
	<input type="text" readonly class="form-control" value="{$details.code}">

</div>
<div class="form-group">
	<label for="note" class="bmd-label-floating">Description</label>
	<textarea class="form-control spec1" id="spec1"  name="spec1">{$details.spec}</textarea>
	{form_error('note')}
</div>

<div class="form-group">
	<label for="quantity" class="bmd-label-floating">Quantity</label>
	<input type="text"  class="form-control" name="quantity" value="{$details.quantity}">
	{form_error('quantity')}
</div>
<div class="form-group">
	<label for="price" class="bmd-label-floating">Price</label>
	<input type="text"  class="form-control" name="price" value="{$details.total_price/$details.quantity}">
	{form_error('price')}
</div>

<input type="hidden" class="form-control" required name="item_id" value="{$item_id}">
<input type="hidden" class="form-control" required name="id" value="{$id1}">
<input type="hidden" class="form-control" required name="vat" value="{$details.vat_perc}">

<div class="card-footer text-right">
	<button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>


	<button type="submit" class="btn btn-primary" name="edit_item" value="edit_item" >Update</button>
</div>

{form_close()}

<script type="text/javascript">
ClassicEditor
.create( document.querySelector( '#spec1' ), {
} )
.then( editor => {
	window.editor = editor;
} )
.catch( err => {
	console.error( err.stack );
} );

</script>