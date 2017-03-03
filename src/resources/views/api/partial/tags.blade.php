<button type="button" class="btn btn-xs btn-info" data-role="tag-editor">
  <i class="fa fa-pencil"></i>
</button>

@foreach($row::getTags($row->key_id)->get() as $tag)
  <span class="label label-info" data-role="tag" data-tag_id="{{ $tag->id }}">{{ $tag->name }} <i class="fa fa-times" style="cursor: pointer;"></i></span>
@endforeach
<input type="text" class="form-control input-sm hidden" data-role="taggable" style="height: 22px;" />