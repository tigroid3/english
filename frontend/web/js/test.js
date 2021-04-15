$(document).ready(function () {
    $(document).on('click', '.send-answer', function () {
        setPhrase($(this));

        return false;
    });

    $(document).on('keypress', function (e) {
        if (e.which == 13 && $(':focus').length === 1) {
            setPhrase($(':focus'));
        }
    });

    $(document).on('click', '.set-success-test-item-button', function () {
        var url = $(this).attr('href');

        console.log(url);
        $.ajax({
            url: url,
            method: 'post',
            success: function () {
                $.pjax.reload({container: "#pjax-test-view"});
            }
        });

        return false;
    });

    $(document).on('click', '.reset-test-item-button', function () {
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            method: 'post',
            success: function () {
                $.pjax.reload({container: "#pjax-test-view"});
            }
        });

        return false;
    });
});

function setPhrase(input) {
    let field = input.parents('tr').find('input.answer-field');
    field.attr('disabled', 'disabled');
    input.parents('tr').find('a.send-answer').hide(0);

    $.ajax({
        url: '/test/set-phrase',
        method: 'post',
        data: {
            phrase: field.val(),
            testItemId: field.data('answer-id')
        },
        success: function (data) {
            if (data.error !== '') {
                alert(data.error);
            }

            $.pjax.reload({container: "#pjax-test-view"});
        }
    });
}
