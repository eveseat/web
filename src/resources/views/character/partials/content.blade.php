<table class="table compact table-condensed table-hover table-responsive" id="assets-contents" data-item-id="{{$row->item_id}}">
  <thead>
  <tr>
    <th></th>
    <th>{{ trans('web::seat.quantity') }}</th>
    <th>{{ trans_choice('web::seat.type', 1) }}</th>
    <th>{{ trans('web::seat.volume') }}</th>
    <th>{{ trans_choice('web::seat.group',1) }}</th>
  </tr>
  </thead>
  <tbody>
  @foreach($row->content as $content)
    <tr>
      <td></td>
      <td>
        @if($content->content->count()< 1)
          {{number($content->quantity, 0)}}
        @endif
      </td>
      <td>
        @each('web::character.partials.asset-type', compact('content'),'row')
      </td>
      <td>{{number_metric($content->quantity * optional($content->type)->volume ?? 0)}}m&sup3</td>
      <td>
        @if($content->type)
          {{$content->type->group->groupName}}
        @else
          Unknown
        @endif
      </td>
      <td>
        @if($content->content->count()>0)
          @each('web::character.partials.content',compact('content'),'row')
        @endif
      </td>
    </tr>
  @endforeach
  </tbody>
</table>