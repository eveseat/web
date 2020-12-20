<button type="button" class="btn btn-sm btn-light" data-widget="esi-update" data-type="{{ $type }}" data-entity="{{ $entity }}" data-job="{{ $job }}">
  <i class="fas fa-sync" data-toggle="tooltip" title="{{ $label }}"></i>
</button>

@push('javascript')
  <script>
    $('button[data-widget="esi-update"]').on('click', function() {
      var button = $(this);
      button.find('i').addClass('fa-spin');

      $.ajax({
        url: '{{ route('tools.jobs.dispatch') }}',
        method: 'POST',
        data: {
          type: button.data('type'),
          entity_id: button.data('entity'),
          job_name: button.data('job')
        },
        success: function() {
          button.find('i').removeClass('fa-spin');
        },
        error: function(e) {
          button.addClass('btn-danger');
          button.removeClass('btn-light');

          console.debug(e);
        }
      });
    });
  </script>
@endpush
