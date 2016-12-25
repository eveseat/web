@extends('web::character.layouts.view', ['viewname' => 'assets'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.assets'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.assets') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans('web::seat.quantity') }}</th>
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
          <th>{{ trans('web::seat.volume') }}</th>
          <th>{{ trans('web::seat.group') }}</th>
        </tr>
        </thead>

        @foreach($assets->unique('location')->groupBy('location') as $location => $data)
          <tbody style="border-top: 0px;">
            <tr class="active">
              <td colspan="4">
                <b>{{ $location }}</b>
                <span class="pull-right">
                  <i>
                  {{ count($assets->where('locationID', $data[0]->locationID)) }}
                    {{ trans('web::seat.items_taking') }}
                    {{ number_metric($assets
                        ->where('locationID', $data[0]->locationID)->map(function($item) {
                          return $item->quantity * $item->volume;
                        })->sum()) }} m&sup3;
                  </i>
                </span>
              </td>
            </tr>
          </tbody>
          @foreach($assets->where('locationID', $data[0]->locationID) as $asset)
            <tbody style="border-top: 0px;">
              <tr>
                @if( $assetContents->where('itemID',$asset->itemID)->count() > 0 )
                  <td><i class="fa fa-plus viewcontent" style="cursor: pointer;"></i></td>
                @else
                <td>{{ $asset->quantity }}</td>
                @endif
                <td>
                  {!! img('type', $asset->typeID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                  {{ $asset->typeName }}
                </td>
                <td>{{ number_metric($asset->quantity * $asset->volume) }} m&sup3;</td>
                <td>{{ $asset->groupName }}</td>
              </tr>
            </tbody>
            @if( $assetContents->where('itemID',$asset->itemID)->count() > 0 )
              <tbody style="display: none;" class="tbodycontent">
                @foreach( $assetContents->where('itemID',$asset->itemID) as $assetContent)
                  <tr class="hidding">
                    <td>{{ $assetContent->quantity }}</td>
                    <td>
                      {!! img('type', $assetContent->typeID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                      {{ $assetContent->typeName }}
                    </td>
                    <td>{{ number_metric($assetContent->quantity * $assetContent->volume) }} m&sup3;</td>
                    <td>{{ $assetContent->groupName }}</td>
                  </tr>
                @endforeach
              </tbody>
            @endif
          @endforeach
        @endforeach

      </table>

    </div><!-- /.box-body -->
  </div>

@stop

@push('javascript')

<script type="text/javascript">

  // First Hide all contents. Not very clean to add a fake class.. TODO: Think another way to do this
  $(".tbodycontent").hide();
  // on button click. Not very clean to add a fake class.. TODO: Think another way to do this
  $(".viewcontent").on("click", function( event ){
    // get the tbody tag direct after the button
    var contents = $(this).closest( "tbody").next( "tbody" );

    // Show or hide
    contents.toggle();

    // some code for stylish
    if (contents.is(":visible")){
      $(this).removeClass('fa-plus').addClass('fa-minus');
      $(this).closest("tr").css( "background-color", "rgb(165, 165, 165)" ); // change the background color of container (for easy see where we are)
      contents.css( "background-color", "rgb(212, 212, 212)" ); // change the background color of content (for easy see where we are)

    } else {
      $(this).removeClass('fa-minus').addClass('fa-plus');
      $(this).closest("tr").css( "background-color", "" ); // reset the background color on container when we hide content
    }
  });

</script>

@endpush
