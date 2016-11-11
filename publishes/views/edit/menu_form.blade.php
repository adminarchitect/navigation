<table class="table">
    @each($template->edit('row'), $form, 'field')

    @include($template->edit('actions'))
</table>