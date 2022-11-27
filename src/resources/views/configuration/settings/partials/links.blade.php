<div class="card">
    <div class="card-header bg-primary-lt">
        <h3 class="card-title">{{ trans('web::seat.customlink') }}</h3>
    </div>
    <form role="form" action="{{ route('seatcore::seat.update.customlink') }}" method="post">
        {{ csrf_field() }}
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <caption>
                    <p>{!! trans('web::seat.customlink_description', [
                        'fa-link' => '<a href="https://fontawesome.com/icons?d=gallery" target="_blank">Font Awesome</a>',
                        'icon' => '<code>fas fa-link</code>',
                    ]) !!}</p>
                </caption>
                <thead>
                    <tr>
                        <th>{{ trans_choice('web::seat.name', 1) }}</th>
                        <th>{{ strtoupper(trans('web::seat.url')) }}</th>
                        <th>Icon</th>
                        <th>{{ trans('web::seat.new_tab_question_mark') }}</th>
                        <th class="w-1"></th>
                    <tr>
                </thead>
                <tbody id="customlinks">

                    @foreach ($custom_links as $node)
                        <tr id="customlink{{ $loop->index }}">
                            <td>
                                <input type="text" class="form-control" name="customlink-name[]"
                                    value="{{ $node->name }}" />
                            </td>
                            <td>
                                <input type="text" class="form-control" name="customlink-url[]"
                                    value="{{ $node->url }}" />
                            </td>
                            <td>
                                <input type="text" class="form-control" name="customlink-icon[]"
                                    value="{{ $node->icon }}" />
                            </td>
                            <td>
                                <select class="form-select" name="customlink-newtab[]">
                                    <option value="1" {{ $node->new_tab === false ? '' : 'selected="selected"' }}>
                                        Yes
                                    </option>
                                    <option value="0" {{ $node->new_tab === false ? 'selected="selected"' : '' }}>
                                        No
                                    </option>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-md ml-2" id="customlink-remove-btn"
                                    onclick="customlink_remove(this)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="d-flex">
                <button type="button" class="btn btn-link" id="customlink-add-btn"><i class="fas fa-plus"></i> Add
                    new link</button>
                <button type="submit" class="btn btn-success ms-auto">
                    <i class="fas fa-check"></i> Update
                </button>
            </div>
        </div>

    </form>
</div>


@push('javascript')
    <script type="text/javascript">
        var customlink_template = '<td>' +
            '<input type="text" class="form-control" name="customlink-name[]" />' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control" name="customlink-url[]" />' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control" name="customlink-icon[]" />' +
            '</td>' +
            '<td>' +
            '<select class="form-select" name="customlink-newtab[]">' +
            '<option value="1" selected="selected">Yes</option>' +
            '<option value="0">No</option>' +
            '</select>' +
            '</td>' +
            '<td>' +
            '<button type="button" class="btn btn-danger btn-md ml-2" id="customlink-remove-btn" onclick="customlink_remove(this)">' +
            '<i class="fas fa-trash-alt"></i></button>' +
            '</td>';

        document.getElementById('customlink-add-btn').onclick = function() {
            var menu_node = document.createElement('tr');
            menu_node.innerHTML = customlink_template;
            document.getElementById('customlinks').appendChild(menu_node);
        };

        function customlink_remove(ele) {
            $(ele).parent().parent().remove();
        }
    </script>
@endpush
