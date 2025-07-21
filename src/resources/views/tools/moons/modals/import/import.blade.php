<div class="modal fade" tabindex="-1" role="dialog" id="moon-import">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">{{ trans('web::moons.probe_report') }}</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('seatcore::tools.moons.store') }}" id="moon-report-form">
          {{ csrf_field() }}
          <div class="form-group">
            <label for="moon-report" class="control-label">{{ trans('web::moons.report') }}</label>
            <textarea class="form-control" name="moon-report" id="moon-report" rows="5"></textarea>
            <p class="form-text text-muted mb-0">
              {!! trans('web::moons.probe_report_instruction') !!}
            </p>
          </div>
          <div class="form-group">
            <label for="notes" class="control-label">{{ trans('web::moons.notes') }}</label>
            <input type="hidden" id="notes" name="notes" value="" />
            <div id="moon-notes" style="max-height: 7em"></div>
            <p class="form-text text-muted mb-0">
              {!! trans('web::moons.notes_instruction') !!}
            </p>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('web::seat.close') }}</button>
        <button type="submit" form="moon-report-form" class="btn btn-primary">{{ trans('web::moons.post_report') }}</button>
      </div>
    </div>
  </div>
</div>

@push('head')
  <link href="{{ asset('web/css/quill.snow.css') }}" rel="stylesheet" />
@endpush

@push('javascript')
  <script src="{{ asset('web/js/quill.min.js') }}"></script>

  <script>
    Quill.prototype.getHtml = function () {
      var html = this.container.querySelector('.ql-editor').innerHTML;
      html = html.replace(/<p>(<br>|<br\/>|<br\s\/>|\s+|)<\/p>\r\n/gmi, "");
      return html;
    };

    var editor = new Quill('#moon-notes', {
      modules: {
        toolbar: [
          [{'header': ['1', '2', '3', '4', '5', '6', false]}, {'color': []}],
          ['bold', 'italic', 'underline', 'strike'],
          [{'list': 'ordered'}, {'list': 'bullet'}],
          [{'align': []}, {'indent': '-1'}, {'indent': '+1'}],
          ['link'],
          ['clean']
        ]
      },
      placeholder: '{{ trans('web::moons.notes_placeholder') }}',
      theme: 'snow'
    });

    $('#moon-report-form').on('submit', function () {
      const input = $('input[name="notes"]');
      input.val(editor.getHtml());
    });


    $('#moon-import').on('show.bs.modal', function (e) {
      $('#components-detail').modal('hide')

      $.ajax($(e.relatedTarget).data('url'))
        .done(function (data) {
          $('#moon-report').val(data.report)
          editor.setContents(editor.clipboard.convert(data.notes), 'silent');
        }).fail(function () {
          $('#moon-report').val('')
          editor.setContents(editor.clipboard.convert(''), 'silent');
      })
    })
  </script>
@endpush
