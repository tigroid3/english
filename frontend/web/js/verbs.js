$(function () {
    $('[data-toggle="tooltip"]').tooltip();

    $('#check').on('click', function () {
        Eng.check();
    });

    $(document).keyup(function (e) {
        var code = e.key;
        if (code === "Enter") {
            Eng.check();
            let data = [];

            $('input').each(function () {
                data.push({
                    tr: $(this).parents('tr').data('number-tr'),
                    val: $(this).val(),
                });
            });

            localStorage.setItem('data', JSON.stringify(data));
        }
    });
});

var Eng = {
    check: function () {
        $('input').each(function () {
            if ($(this).data('val') !== $(this).val()) {
                $(this).removeClass('border-success').addClass('border-danger');
            } else {
                $(this).removeClass('border-danger').addClass('border-success');
            }
        });
    }
};
