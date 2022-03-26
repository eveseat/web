<select multiple="multiple" autocomplete="off" class="form-select" id="dt-character-selector" @if(! $character->refresh_token) disabled="disabled" @endif>
    @if($character->refresh_token)
        @foreach($character->refresh_token->user->characters as $character_info)
            @if($character_info->character_id == $character->character_id)
                <option value="{{ $character_info->character_id }}" selected="selected">{{ $character_info->name }}</option>
            @else
                <option value="{{ $character_info->character_id }}">{{ $character_info->name }}</option>
            @endif
        @endforeach
    @else
        <option value="{{ $character->character_id }}" selected="selected">{{ $character->name }}</option>
    @endif
</select>

@push('javascript')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.TomSelect && (new TomSelect(document.getElementById('dt-character-selector'), {
                copyClassesToDropdown: false,
                dropdownClass: 'dropdown-menu',
                optionClass:'dropdown-item',
                onChange: function() { window.LaravelDataTables['dataTableBuilder'].ajax.reload(); },
                render:{
                    item: function(data,escape) {
                        return '<div><span class="dropdown-item-indicator"><span class="avatar avatar-xs" style="background-image: url(https://images.evetech.net/characters/' + escape(data.value) + '/portrait?size=32)"></span></span>' + escape(data.text) + '</div>';
                    },
                    option: function(data,escape){
                        return '<div><span class="dropdown-item-indicator"><span class="avatar avatar-xs" style="background-image: url(https://images.evetech.net/characters/' + escape(data.value) + '/portrait?size=32)"></span></span>' + escape(data.text) + '</div>';
                    },
                }
            }));
        });
    </script>
@endpush
