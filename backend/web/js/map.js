var map = {
    clearItems: function(clear_elem){
        $(clear_elem).each(function(index, value){
            $(value).empty();
        })
    },
    getItems: function(elem, url, write_elem, clear_elems){
        this.clearItems(clear_elems);

        $.ajax({
            url: url,
            data: {parent: elem.val()},
            success: function(response){
                $(write_elem).html(response);
            }
        })
    }
};

$("body").on("change", ".from-country", function(){
    map.getItems($(this), '/map/get-regions', '.from-region', '.inner-from-country');
})

$("body").on("change", ".to-country", function(){
    map.getItems($(this), '/map/get-regions', '.to-region', '.inner-to-country');
})

$("body").on("change", ".from-region", function(){
    map.getItems($(this), '/map/get-cities', '.from-city', '.inner-from-region');
})

$("body").on("change", ".to-region", function(){
    map.getItems($(this), '/map/get-cities', '.to-city', '.inner-to-region');
})