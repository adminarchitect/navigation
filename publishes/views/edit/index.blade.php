@inject('form', 'scaffold.form')
@inject('module', 'scaffold.module')
@inject('actions', 'scaffold.actions')
@inject('template', 'scaffold.template')

@extends($template->layout())

@section('scaffold.create')
    @include($template->edit('create'))
@endsection

@section('scaffold.content')
    @if (isset($item))
        <div class="row">
            <div class="col-xs-4">
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="box-group" id="accordion">
                            @foreach(app('admin.navigation')->providers() as $provider)
                                <?php
                                $expanded = $loop->index == 0;
                                ?>
                                @if (class_basename($provider) == 'LinksProvider')
                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#links-list" aria-expanded="false" class="collapsed">{{ app(\App\Http\Terranet\Administrator\Navigation\Providers\LinksProvider::class)->name() }}</a>
                                            </h4>
                                        </div>
                                        <div id="links-list" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="url">{{ trans('navigation::general.link.url') }}:</label>
                                                    <input type="url" class="form-control" data-name="url" value="http://example.com">
                                                </div>
                                                <div class="form-group">
                                                    <div style="max-height: 250px; overflow-y: auto; margin-bottom: 20px;">
                                                        <label for="title">{{ trans('navigation::general.link.title') }}:</label>
                                                        <input type="text" class="form-control" data-name="title" value="Example">
                                                    </div>

                                                    <input type="button" class="btn btn-primary pull-right push-link" value="{{ trans('navigation::general.buttons.add_to_menu') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#{{ $slug = str_slug($name = $provider->name()) }}-list" aria-expanded="{{ $expanded ? 'true' : 'false' }}" class="{{ $expanded ? '' : 'collapsed' }}">{{ $name }}</a>
                                            </h4>
                                        </div>

                                        <div id="{{$slug}}-list" class="panel-collapse collapse {{ $expanded ? 'in' : '' }}" aria-expanded="{{ $expanded ? 'true' : 'false' }}" {{ $expanded ? '' : 'style="height: 0px;"' }}>
                                            <div class="box-body">
                                                <div class="provider-links" style="max-height: 250px; overflow-y: auto; margin-bottom: 20px;">
                                                    <ul class="list-unstyled">
                                                        @foreach($provider as $link)
                                                            <li>
                                                                <label for="link_{{ $slug }}_{{ $link->id() }}">
                                                                    <input type="checkbox" name="navigable[{{ get_class($provider) }}][]" value="{{ $link->id() }}" id="link_{{ $slug }}_{{ $link->id() }}">
                                                                    {{ $link->title() }}
                                                                </label>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <input type="button" class="btn btn-primary pull-right push-navi-items" value="{{ trans('navigation::general.buttons.add_to_menu') }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div>
            <div class="col-xs-8">
                {!! Form::model(isset($item) ? $item : null, ['method' => 'post', 'files' => true]) !!}
                @include('navigation::edit.menu_form')

                <p class="text-primary">
                    <i class="fa fa-info-circle"></i>&nbsp;
                    {{ trans('navigation::general.hierarchy') }}
                </p>

                <div id="navigation-items" class="dd">
                    {!! nestable_menu($item) !!}
                </div>

                {!! Form::close() !!}

                <div class="clearfix"></div>
            </div>
        </div>
    @else
        {!! Form::model(isset($item) ? $item : null, ['method' => 'post', 'files' => true]) !!}
        @include('navigation::edit.menu_form')
        {!! Form::close() !!}
    @endif
@stop

@include($template->edit('scripts'))

@section('scaffold.css')
    <link rel="stylesheet" href="{{ asset($file = 'navigation/css/jquery.nestable.css') . '?' . filemtime(public_path($file)) }}">
@append

@section('scaffold.js')
    <script src="{{ asset($file = 'navigation/js/jquery.nestable.js') . '?' . filemtime(public_path($file)) }}"></script>
    <script src="{{ asset($file = 'navigation/js/navigation.js') . '?' . filemtime(public_path($file)) }}"></script>

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
@append
