<a href="{{ route('kill.job', ['key_id' => $row->job_id]) }}" type="button"
   class="btn btn-danger btn-xs confirmlink">
  {{ trans('web::seat.kill') }}
</a>
