jQuery(document).ready(function() {
    var searchRequest = null;
    jQuery("#search").keyup(function() {
        var minlength = 3;
        var that = this;
        var value = jQuery(this).val();
        var entitySelector = jQuery("#entitiesNav").html('');
        if (value.length >= minlength ) {
            if (searchRequest != null)
                searchRequest.abort();
            searchRequest = jQuery.ajax({
                type: "GET",
                url: "http://127.0.0.1:8000/quiz/own",
                data: {
                    'q' : value
                },
                dataType: "text",
                success: function(msg){
                    //we need to check if the value is the same
                    if (value==jQuery(that).val()) {
                        var result = JSON.parse(msg);
                        jQuery.each(result, function(key, arr) {
                            jQuery.each(arr, function(id, value) {
                                if (key == 'entities') {
                                    if (id != 'error') {
                                        entitySelector.append('<li><a href="/daten/'+id+'">'+value+'</a></li>');
                                    } else {
                                        entitySelector.append('<li class="errorLi">'+value+'</li>');
                                    }
                                }
                            });
                        });
                    }
                }
            });
        }
    });
});