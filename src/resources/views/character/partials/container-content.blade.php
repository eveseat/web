<div class="modal fade" id="modal-{{$container->item_id}}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Default Modal</h4>
      </div>
      <div class="modal-body">
        <table class="table compact table-condensed table-hover table-responsive">
          <tbody>
          @foreach($container->content as $cargo)
            <tr>
              <td>
                @if($cargo->content->count() > 0)
                  <button class="btn btn-xs btn-link viewcontent">
                    <i class="fa fa-plus"></i>
                  </button>
                @endif
              </td>
              <td>{{ number($cargo->quantity, 0) }}</td>
              <td>{!! img('type', $cargo->type_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!} {{ $cargo->type->typeName }}</td>
              <td>{{ number_metric($cargo->quantity * $cargo->type->volume) }}m&sup3;</td>
              <td>{{ $cargo->type->group->groupName }}</td>
            </tr>
            @if($cargo->content->count() > 0)
              <tr style="display: none;">
                <td colspan="5">
                  <table class="table compact table-condensed table-hover table-responsive">
                    <tbody>
                    @foreach($cargo->content as $cargo2)
                      <tr>
                        <td></td>
                        <td>{{ number($cargo2->quantity, 0) }}</td>
                        <td>{!! img('type', $cargo2->type_id, 32, ['class' => 'img-circle eve-icon small-icon'], false) !!} {{ $cargo->type->typeName }}</td>
                        <td>{{ number_metric($cargo2->quantity * $cargo2->type->volume) }}m&sup3;</td>
                        <td>{{ $cargo2->type->group->groupName }}</td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                </td>
              </tr>
            @endif
          @endforeach
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>