<div class="modal fade in" tabindex="-1" role="dialog" id="character-transfer-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <h4 class="modal-title">Transfer</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('seatcore::configuration.users.reassign', $user->id) }}" id="character-transfer-form">
          {!! csrf_field() !!}
          {!! method_field('PUT') !!}
          <input type="hidden" name="character" value="0" />

          <div class="form-group">
            <label for="target-user-lookup" class="col-form-label">Choose the target user</label>
            <select name="user" id="target-user-lookup" style="width: 100%"></select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button form="character-transfer-form" type="submit" class="btn btn-success">Confirm</button>
      </div>
    </div>
  </div>
</div>

@push('javascript')
  <script>
    var select2_lookup_excluding_user = 0;

    $('#character-transfer-modal').on('show.bs.modal', function (e) {
        select2_lookup_excluding_user = $(e.relatedTarget).data('user');
        $(this).find('[name=character]').val($(e.relatedTarget).data('character'));
    });

    $('#target-user-lookup')
      .select2({
        placeholder: 'Choose target user',
        dropdownParent: '#character-transfer-modal',
        ajax: {
          url: '{{ route('seatcore::fastlookup.users') }}',
          dataType: 'json',
          cache: true,
          data: function (params) {
            return {
              term: params.term,
              q: params.term,
              exclude: select2_lookup_excluding_user
            }
          },
          processResults: function (data, params) {
            return {
              results: data.results
            };
          }
        },
        templateResult: function(group) {
          var html = '';

          if (! group.type)
            return group.text;

          html += '<span data-id="' + group.character_id + '">' + group.img[0] + ' ' + group.text + '</span> ';

          for (var i = 1; i < group.img.length; i++) {
            html += group.img[i] + ' ';
          }

          return $(html);
        },
        minimumInputLength: 3
      });
  </script>
@endpush