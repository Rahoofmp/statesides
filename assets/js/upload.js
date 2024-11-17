var abc = 0;      // Declaring and defining global increment variable.
var rootPath = $("#rootPath").val();
 
$(document).ready(function() { 
	$('#add_more').click(function() {

		var files_count =  parseInt( $('.image-files').length );
 console.log(files_count);

		var allocate_html ='<div class="card-body"><label>Upload Chapter</label><div class="fileupload fileupload-new" data-provides="fileupload"><div class="fileupload-new thumbnail"><img src="'+rootPath+'assets/images/course_chapters/bg.jpg" alt=""  style="width:100px; height:100px;" ></div><div class="fileupload-preview fileupload-exists thumbnail"></div><div class="user-edit-image-buttons"><span class="btn btn-azure btn-file btn btn-info btn-sm tooltips edit_hsn"><span class="fileupload-new"><i class="fa fa-picture"></i>Select Doc</span><span class="btn btn-azure btn-file btn btn-info btn-sm tooltips edit_hsn"><i class="fa fa-picture"></i>Change</span><input type="file" id="userfile_' + files_count +'" name ="userfile_' + files_count +'" class="image-files"></span><a href="#" class="btn btn-azure btn-file btn btn-info btn-sm tooltips edit_hsn" data-dismiss="fileupload"><i class="fa fa-times"></i>Remove</a></div></div></div><div class="card-body"><label class="control-label">Heading</label><span class="symbol required"></span><input type="text" class="form-control" id="image_des_' + files_count +'" name="image_des_' + files_count +'" autocomplete="Off"></div><div class="card-body"><label class="control-label">Order Id</label><span class="symbol required"></span><input type="number" class="form-control" id="order_id_' + files_count +'" name="order_id_' + files_count +'" autocomplete="Off"></div>';

		$(this).before($("<div/>", {
			id: 'filediv'
		}).fadeIn('slow').append(allocate_html));
	});
	// Following function will executes on change event of file input to select different file.
	$('body').on('change', '#file', function() {
		if (this.files && this.files[0]) {
			// Incrementing global variable by 1.
			abc += 1; 
			var z = abc - 1;
			var x = $(this).parent().find('#previewimg' + z).remove();
			$(this).before("<div id='abcd" + abc + "' class='abcd'><img id='previewimg" + abc + "' src=''/></div>");
			var reader = new FileReader();
			reader.onload = imageIsLoaded;
			reader.readAsDataURL(this.files[0]);
			$(this).hide();
			$("#abcd" + abc).append($("<img/>", {
				id: 'img',
				src: 'x.png',
				alt: 'delete'
			}).click(function() {
				$(this).parent().parent().remove();
			}));
		}
	});
	// To Preview Image
	function imageIsLoaded(e) {
		$('#previewimg' + abc).attr('src', e.target.result);
	};
	$('#upload').click(function(e) {
		var name = $(":file").val();
		if (!name) {
			alert("First Image Must Be Selected");
			e.preventDefault();
		}
	});
});