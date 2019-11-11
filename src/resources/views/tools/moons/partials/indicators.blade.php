<span class="badge badge-success">{{ $row->moon_contents->filter(function ($content) use ($row) { return $content->type->marketGroupID == 2396; })->count() }}</span>
<span class="badge badge-primary">{{ $row->moon_contents->filter(function ($content) { return $content->type->marketGroupID == 2397; })->count() }}</span>
<span class="badge badge-info">{{ $row->moon_contents->filter(function ($content) { return $content->type->marketGroupID == 2398; })->count() }}</span>
<span class="badge badge-warning">{{ $row->moon_contents->filter(function ($content) { return $content->type->marketGroupID == 2400; })->count() }}</span>
<span class="badge badge-danger">{{ $row->moon_contents->filter(function ($content) { return $content->type->marketGroupID == 2401; })->count() }}</span>
<span class="badge badge-default">{{ $row->moon_contents->filter(function ($content) { return ! in_array($content->type->marketGroupID, [2396, 2397, 2398, 2400, 2401]); })->count() }}</span>
