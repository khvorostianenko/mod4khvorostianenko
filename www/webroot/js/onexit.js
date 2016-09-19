//Это рабочий вариант (#2)
function Unloader(){

    var o = this;

    this.unload = function(evt)
    {
        var message = "Вы уверены, что хотите покинуть страницу оформления заказа?";
        if (typeof evt == "undefined") {
            evt = window.event;
        }
        if (evt) {
            evt.returnValue = message;
        }
        return message;
    }

    this.resetUnload = function()
    {
        $(window).off('beforeunload', o.unload);

         setTimeout(function(){
            $(window).on('beforeunload', o.unload);
        }, 2000);
    }

    this.init = function()
    {

        $(window).on('beforeunload', o.unload);

        $('a').on('click', function(){o.resetUnload});

            $(document).on('submit', 'form', function(){o.resetUnload});
        $(document).on('keydown', function(event){
            if((event.ctrlKey && event.keyCode == 116) || event.keyCode == 116){
                o.resetUnload;
            }
        }
        );
    }
    this.init();
}

$(function(){
    if(typeof window.obUnloader != 'object')
    {
        window.obUnloader = new Unloader();
    }
})


//Это рабочий вариант (#1)
//window.onbeforeunload = function()
//{
//    return confirm('Вы хотите покинуть сайт?')
//}

//Мусор 3
//$(document).ready(function () {
//  $(window).on('beforeunload', function() {
//   return " тест "; // СООБЩЕНИЕ ПРИ ЗАКРЫТИИ!
//
//  $('a').click(function() {
//    $(window).off('beforeunload');
//  });
//});

//Мусор 1
//window.onbeforeunload = function(){
//    return confirm('Точно хотите выйти?');
//}

//Мусор 2
//window.onbeforeunload = function() {
//    return "Что-нибудь сообщить пользователю";
//}