function autoComplete(element, userType, path){
    var rootPath = $("#rootPath").val();

    $( element ).autocomplete({
        source: function(request, response) {
            $.ajax({
                url: rootPath + userType + '/' + path,
                dataType: 'json',
                data: { keyword : element.value },
                type: 'post',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['result']
                        }
                    }));
                }
            });
        }
    });
}
function autoCompletePackage(element, userType, path, project_id){
    var rootPath = $("#rootPath").val();

    $( element ).autocomplete({
        source: function(request, response) {
            $.ajax({
                url: rootPath + userType + '/' + path,
                dataType: 'json',
                data: { keyword : element.value, project_id: project_id },
                type: 'post',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['result']
                        }
                    }));
                }
            });
        }
    });
}