@inject('form', 'scaffold.form')
@inject('module', 'scaffold.module')
@inject('actions', 'scaffold.actions')
@inject('template', 'scaffold.template')

@extends($template->layout())

@section('scaffold.create')
    @include($template->edit('create'))
@endsection

@section('scaffold.content')
    {!! Form::model(isset($item) ? $item : null, ['method' => 'post', 'files' => true]) !!}
    @if (isset($item))
        <div class="row">
            <div class="col-xs-4">
                @include('navigation::edit.providers')
            </div>
            <div class="col-xs-8">

                @include('navigation::edit.menu_form')

                <div id="navigation-items" class="dd">
                    {!! nestable_menu($item) !!}
                </div>

                <div class="clearfix"></div>

                @if ($item->rootItems->count())
                <hr />
                <p class="text-2primary text-muted">
                    <i class="fa fa-info-circle"></i>&nbsp;
                    {{ trans('navigation::general.hierarchy') }}
                </p>
                @endif
            </div>
        </div>
    @else
        @include('navigation::edit.menu_form')
    @endif
    {!! Form::close() !!}
@stop

@include($template->edit('scripts'))

@push('scaffold.css')
    <link rel="stylesheet" href="{{ asset($file = 'admin/navigation/nestable.css') }}">
@endpush

@push('scaffold.js')
    <script src="{{ asset($file = 'admin/navigation/nestable.js') . '?' . filemtime(public_path($file)) }}"></script>
    <script src="{{ asset($file = 'admin/navigation/navigation.js') . '?' . filemtime(public_path($file)) }}"></script>
    <script type="text/html" id="navigable-template">
        <li class="dd-item dd3-item" data-id="{identifier}">
            <div class="dd-handle dd3-handle">&nbsp;</div>
            <div class="dd3-content">
                <a href="#" class="remove-navigable pull-right" style="margin-left: 10px;" data-confirmation="{{ trans('navigation::general.remove_confirmation') }}">&times;</a>
                <span class="text-muted pull-right">{provider}</span>
                <strong class="pull-left">{title}</strong>
                <span>{input}</span>
                <div class="clearfix"></div>
            </div>
        </li>
    </script>
@endpush
