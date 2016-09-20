(function($) {
    $(function() {
            function getWindow(){
                        seconds = $('#subscriber_timer').html();
                        seconds = seconds*1 + 1;
                        if(seconds < 16){
                            $('#subscriber_timer').html(seconds);
                            forNewDom('/pages/seconds/'+seconds, '', 'show_timer');
                        }
                        if(seconds == 15){
                               $('.offer').arcticmodal({
                                  closeOnOverlayClick: false,
                                  closeOnEsc: true
                               });

                        }
            };
            setInterval (getWindow, 1000);
    })
})(jQuery)

