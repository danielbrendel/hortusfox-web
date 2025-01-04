<h1>{{ __('app.inventory') }}</h1>

<h2 class="smaller-headline">{{ __('app.inventory_hint') }}</h2>

@include('flashmsg.php')

<div>
    <div class="margin-vertical is-inline-block">
        <a class="button is-success" href="javascript:void(0);" onclick="window.vue.bShowAddInventoryItem = true;">{{ __('app.create_new') }}</a>
        &nbsp;
        <a class="button is-link" href="javascript:void(0);" onclick="window.vue.bShowManageGroups = true;">{{ __('app.manage_groups') }}</a>
        &nbsp;
        <a class="button" href="javascript:void(0);" onclick="window.vue.bShowInventoryBulkPrint = true;">{{ __('app.bulk_print_qr_codes') }}</a>
        &nbsp;
        <a class="button is-warning" href="javascript:void(0);" onclick="window.vue.bShowInventoryExport = true;">{{ __('app.export') }}</a>
    </div>

    <div class="sorting-control is-rounded is-small is-inline-block is-next-to-elem">
        <input type="text" id="inventory-filter" placeholder="{{ __('app.filter_by_text') }}">
    </div>
</div>

@if (isset($inventory))
    <div class="inventory">
        <?php $lastCat = ''; ?>
        @for ($i = 0; $i < $inventory->count(); $i++)
            <?php 
                if ($lastCat !== $inventory->get($i)->get('group_ident')) {
                    $lastCat = $inventory->get($i)->get('group_ident');

                    echo '<div class="inventory-item-group">' . InvGroupModel::getLabel($inventory->get($i)->get('group_ident')) . '</div>';
                } 
            ?>

            <div class="inventory-item" id="inventory-item-{{ $inventory->get($i)->get('id') }}">
                <a name="anchor-item-{{ $inventory->get($i)->get('id') }}"></a>

                <div class="inventory-item-header">
                    <div class="inventory-item-name" id="inventory-item-name-{{ $inventory->get($i)->get('id') }}"><span>#{{ sprintf('%03d', $inventory->get($i)->get('id')) }}</span> <a href="javascript:void(0);" onclick="window.vue.expandInventoryItem('inventory-item-body-{{ $inventory->get($i)->get('id') }}');">{{ $inventory->get($i)->get('name') }}</a></div>

                    <div class="inventory-item-amount">
                        <a href="javascript:void(0);" onclick="window.vue.decrementInventoryItem({{ $inventory->get($i)->get('id') }}, 'inventory-item-amount-{{ $inventory->get($i)->get('id') }}');"><i class="fas fa-chevron-left"></i></a>
                        <span id="inventory-item-amount-{{ $inventory->get($i)->get('id') }}" class="{{ ($inventory->get($i)->get('amount') == 0) ? 'is-inventory-item-empty' : '' }}">{{ $inventory->get($i)->get('amount') }}</span>
                        <a href="javascript:void(0);" onclick="window.vue.incrementInventoryItem({{ $inventory->get($i)->get('id') }}, 'inventory-item-amount-{{ $inventory->get($i)->get('id') }}');"><i class="fas fa-chevron-right"></i></a>
                    </div>

                    <div class="inventory-item-actions">
                        <a href="javascript:void(0);" onclick="document.getElementById('title-inventory-qr-code').value = '#{{ $inventory->get($i)->get('id') }} ' + document.getElementById('inventory-item-name-{{ $inventory->get($i)->get('id') }}').innerText; window.vue.queryInvQrCode({{ $inventory->get($i)->get('id') }});"><i class="fas fa-qrcode"></i></a>&nbsp;
                        <a href="javascript:void(0);" onclick="window.vue.editInventoryItem({{ $inventory->get($i)->get('id') }}, 'inventory-item-name-{{ $inventory->get($i)->get('id') }}', '{{ $inventory->get($i)->get('group_ident') }}', 'inventory-item-location-{{ $inventory->get($i)->get('id') }}', 'inventory-item-description-{{ $inventory->get($i)->get('id') }}', {{ $inventory->get($i)->get('amount') }});"><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0);" onclick="window.vue.deleteInventoryItem({{ $inventory->get($i)->get('id') }}, 'inventory-item-{{ $inventory->get($i)->get('id') }}');"><i class="fas fa-times"></i></a>
                    </div>
                </div>

                <div class="inventory-item-body" id="inventory-item-body-{{ $inventory->get($i)->get('id') }}">
                    <div class="inventory-item-description" id="inventory-item-description-{{ $inventory->get($i)->get('id') }}">
                        <pre>
                            @if ((is_string($inventory->get($i)->get('description'))) && (strlen($inventory->get($i)->get('description')) > 0))
                                {!! UtilsModule::translateURLs($inventory->get($i)->get('description')) !!}
                            @else
                                {{ 'N/A' }}
                            @endif
                        </pre>
                    </div>

                    <div class="inventory-item-photo">
                        @if ($inventory->get($i)->get('photo'))
                            @if (file_exists(public_path('/img/' . $inventory->get($i)->get('photo'))))
                                <img src="{{ asset('/img/' . $inventory->get($i)->get('photo')) }}" alt="photo"/>
                            @else
                                <img src="{{ $inventory->get($i)->get('photo') }}" alt="photo"/>
                            @endif
                        @else
                            <p>{{ __('app.no_photo_available') }}</p>
                        @endif
                    </div>

                    <div class="inventory-item-footer">
                        <div class="inventory-item-location" id="inventory-item-location-{{ $inventory->get($i)->get('id') }}">
                            {!! __('app.location_fmt', ['loc' => ($inventory->get($i)->get('location')) ?? 'N/A']) !!}
                        </div>

                        <div class="inventory-item-author">
                            {{ __('app.last_edited_by', ['name' => (($inventory->get($i)->get('last_edited_user')) ? UserModel::getNameById($inventory->get($i)->get('last_edited_user')) : 'System'), 'when' => (new Carbon($inventory->get($i)->get('last_edited_date')))->diffForHumans()]) }}
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
@endif
