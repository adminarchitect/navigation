<div id="accordion" class="panel-group" role="tablist">
    @foreach(app('admin.navigation')->providers() as $provider)
        <?php
        $expanded = $loop->index == 0;
        ?>
        @if (class_basename($provider) == 'LinksProvider')
            <div class="panel">
                <div class="panel-heading" role="tab">
                    <h3 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#links-list" aria-expanded="false" class="collapsed">
                            {{ app(\App\Http\Terranet\Administrator\Navigation\Providers\LinksProvider::class)->name() }}
                        </a>
                    </h3>
                </div>
                <div id="links-list" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="url">{{ trans('navigation::general.link.url') }}:</label>
                            <input type="url" class="form-control" data-name="url" value="http://example.com">
                        </div>
                        <div class="form-group">
                            <div style="max-height: 250px; overflow-y: auto; margin-bottom: 20px;">
                                <label for="title">{{ trans('navigation::general.link.title') }}:</label>
                                <input class="form-control" data-name="title" value="Example">
                            </div>

                            <input type="button" class="btn btn-primary pull-right push-link" value="{{ trans('navigation::general.buttons.add_to_menu') }}">
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#{{ $slug = str_slug($name = $provider->name()) }}-list" aria-expanded="{{ $expanded ? 'true' : 'false' }}" class="{{ $expanded ? '' : 'collapsed' }}">{{ $name }}</a>
                    </h3>
                </div>

                <div id="{{$slug}}-list" class="panel-collapse collapse {{ $expanded ? 'in' : '' }}" aria-expanded="{{ $expanded ? 'true' : 'false' }}" {{ $expanded ? '' : 'style="height: 0px;"' }}>
                    <div class="panel-body">
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
