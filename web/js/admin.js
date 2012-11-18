(function($) {
    $(function(){
         $('a.confirm').click(function (e) {
            e.preventDefault();
            $('#confirm_dialog')
                .modal('show')
                .find('a.btn-primary').attr('href', $(this).attr('href'))
            ;
        });
    });
})(jQuery);
