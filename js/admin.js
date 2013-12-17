
function setFildsType(){
    var type = $("input[name='Page[type]']:checked").attr('value');
    switch(type){
        case 'static':
            $("[name='Page[content]']").parents("div.row").show();
            $("[name='Page[URL]']").parents("div.row").hide();
            break;
        case 'URL':
            $("[name='Page[URL]']").parents("div.row").show();
            $("[name='Page[content]']").parents("div.row").hide();
            break;
    }
}

$(document).ready(function(){
    setFildsType();
    $("input[name='Page[type]']").change(function(){
        setFildsType();
    });
});