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
                                @if (get_class($provider) == \Terranet\Navigation\Providers\LinksProvider::class)
                                    <div class="panel box box-primary">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#links-list" aria-expanded="false" class="collapsed">{{ app(\Terranet\Navigation\Providers\LinksProvider::class)->name() }}</a>
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
            <div class="col-xs-8" id="sortable-container">
                {!! Form::model(isset($item) ? $item : null, ['method' => 'post', 'files' => true]) !!}
                @include('navigation::edit.menu_form')

                <li data-template="navigable" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; display:none;" class="ui-state-default">
                    <div>
                        <a href="#" class="remove-navigable pull-right" style="margin-left: 10px;">&times;</a>
                        <span class="text-muted pull-right" data-template="provider"></span>
                        <strong class="pull-left" data-template="title"></strong>
                        <span data-template="input"></span>
                        <span data-template="ranking"></span>
                    </div>
                    <div class="clearfix"></div>
                </li>

                <ol id="navigation-items" class="sortable" style="padding: 10px;">
                    @foreach($item as $link)
                        {{--{{ dump($link) }}--}}
                        <li style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;" class="ui-state-default" data-id="{{ $link['id'] }}">
                            <div>
                                <input type="hidden" name="ranking[]" value="{{ $link['id'] }}">
                                <a href="#" class="remove-navigable pull-right" style="margin-left: 10px;">&times;</a>
                                <span class="text-muted pull-right" data-template="provider">{{ $link['provider'] }}</span>
                                <strong class="pull-left" data-template="title">{{ $link['object']->title() }}</strong>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                    @endforeach
                </ol>

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
    <style>
        ol.sortable,
        ol.sortable ol {
            list-style: none;
        }

        .ui-state-highlight {
            height: 3em;
            line-height: 1.2em;
            margin-bottom: 10px;
            background: #DDD;
            border: #919191;
        }
    </style>
@append

@section('scaffold.js')
    <script>
        $(function () {
            $("#navigation-items").sortable({
                placeholder: 'ui-state-highlight',
                // fix bug with wrong draggable position
                helper: function (event, ui) {
                    var $clone = $(ui).clone();
                    $clone.css('position', 'absolute');

                    return $clone.get(0);
                }
            });

            $(document).on('click', '.remove-navigable', function () {
                if (window.confirm('{{ trans('navigation::general.remove_confirmation') }}')) {
                    $(this).closest('.ui-state-default').remove();
                }

                return false;
            });

            function buildNavigable(provider, label, name, value) {
                var navigable = document.querySelector('[data-template="navigable"]').cloneNode(true);
                navigable.querySelector('[data-template="provider"]').textContent = provider;
                navigable.querySelector('[data-template="title"]').textContent = label;
                $(navigable.querySelector('[data-template="input"]'))
                    .replaceWith(
                        '<input type="hidden" name="' + name + '" value="' + value + '" />'
                    );

                $(navigable.querySelector('[data-template="ranking"]'))
                    .replaceWith(
                        '<input type="hidden" name="ranking[]" value="' + name.replace('navigable[', '').replace('][]', '::' + value) + '" />'
                    );

                $(navigable).show().appendTo('#navigation-items');
            }

            $('.push-navi-items').click(function () {
                var checked = $(this).prev('.provider-links').find('ul>li>label>input:checked');

                $.each(checked, function (i, element) {
                    // Create element from Template

                    var label = $(element).closest('label');
                    var provider = $(element).closest('.panel').find('.box-title a');

                    buildNavigable(provider.text(), label.text(), element.name, element.value);

                    $(element).prop('checked', false);
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
    </script>
@append
