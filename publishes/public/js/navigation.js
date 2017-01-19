function initNestable() {
    var $nestable = $("#navigation-items").nestable();
    $nestable.on('change', function () {
        $('#ranking').val(JSON.stringify($nestable.nestable('serialize')));
    }).trigger('change');

    return $nestable;
}

function buildNavigable(provider, label, name, value) {
    var template = document.querySelector('#navigable-template').innerHTML;
    template = template
        .replace('{identifier}', value)
        .replace('{provider}', provider)
        .replace('{title}', label)
        .replace('{input}', '<input type="hidden" name="' + name + '" value="' + value + '" />');

    $(template).appendTo($('#navigation-items > .dd-list'));
}

$(function () {
    var $nestable = initNestable();

    $(document).on('click', '.remove-navigable', function (event) {
        event.preventDefault();
        var e = $(this);
        if (window.confirm(e.data('confirmation'))) {
            e.closest('.dd-item').remove();
            $nestable.trigger('change');
        }

        return false;
    });

    $('.push-navi-items').click(function () {
        var checked = $(this).prev('.provider-links').find('ul>li>label>input:checked');

        $.each(checked, function (i, element) {
            var label = $(element).closest('label');
            var provider = $(element).closest('.panel').find('.box-title a');

            buildNavigable(provider.text(), label.text(), element.name, element.value);

            $(element).prop('checked', false);

            $nestable.trigger('change');
        });

        return false;
    });

    $('.push-link').click(function () {
        var provider = $(this).closest('.panel').find('.box-title a');

        var title = $('[data-name="title"]').val();
        var url = $('[data-name="url"]').val();

        buildNavigable(provider.text(), title, "navigable[Links][" + url + "]", title);
    });
});