<div class="btn-group">
  <a href="{{ route('api.key.detail', ['key_id' => $row->key_id]) }}" type="button"
     class="btn btn-primary btn-sm col-xs-6">
    {{ trans_choice('web::seat.detail', 2) }}
  </a>
  <a href="{{ route('api.key.delete', ['key_id' => $row->key_id]) }}" type="button"
     class="btn btn-danger btn-sm confirmlink col-xs-6">
    {{ trans('web::seat.delete') }}
  </a>
</div>
