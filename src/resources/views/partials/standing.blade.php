@switch (true)
  @case($standing > 5)
    <b class="text-blue">{{ $standing }}</b>
  @break
  @case($standing > 0)
    <b class="text-info">{{ $standing }}</b>
  @break
  @case($standing < -5)
    <b class="text-red">{{ $standing }}</b>
  @break
  @case($standing < 0)
    <b class="text-orange">{{ $standing }}</b>
  @break
  @default
    <b class="text-gray">{{ $standing }}</b>
@endswitch