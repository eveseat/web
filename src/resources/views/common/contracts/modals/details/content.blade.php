<h4>Parties</h4>

<table class="table table-sm table-condensed no-border">
  <thead>
    <tr>
      <th class="col-md-4 text-center">Issuer</th>
      <th class="col-md-4 text-center">Assignee</th>
      <th class="col-md-4 text-center">Acceptor</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="text-center">
        @include('web::partials.character', ['character' => $contract->issuer])
      </td>
      <td class="text-center">
        @include('web::partials.character', ['character' => $contract->assignee])
      </td>
      <td class="text-center">
        @if($contract->acceptor && $contract->acceptor->entity_id != 0)
          @include('web::partials.character', ['character' => $contract->acceptor])
        @endif
      </td>
    </tr>
  </tbody>
</table>

<h4>Area</h4>

<table class="table table-sm table-condensed no-border">
  <tbody>
    <tr>
      <th>Start Location</th>
      <td>{{ $contract->start_location->name }} - {{ $contract->start_location->system->constellation->itemName }} - {{ $contract->start_location->system->region->itemName }}</td>
    </tr>
    <tr>
      <th>End Location</th>
      <td>{{ $contract->end_location->name }} - {{ $contract->end_location->system->constellation->itemName }} - {{ $contract->end_location->system->region->itemName }}</td>
    </tr>
  </tbody>
</table>

<h4>Specifications</h4>

<table class="table table-sm table-condensed no-border">
  <tbody>
    <tr>
      <th>Type</th>
      <td>
        @switch($contract->type)
          @case('item_exchange')
          <span class="fa fa-exchange"></span>
          @break
          @case('auction')
          <span class="fa fa-gavel"></span>
          @break
          @case('courier')
          <span class="fa fa-truck"></span>
          @break
          @case('loan')
          <span class="fa fa-handshake-o"></span>
          @break
        @endswitch
        {{ trans(sprintf('web::contract.%s', $contract->type)) }}
      </td>
    </tr>
    <tr>
      <th>Status</th>
      <td>
        @switch($contract->status)
          @case('cancelled')
          <span class="text-orange"><i class="fa fa-ban"></i> {{ trans(sprintf('web::contract.%s', $contract->status)) }}</span>
          @break
          @case('deleted')
          <span><i class="fa fa-trash"></i> {{ trans(sprintf('web::contract.%s', $contract->status)) }}</span>
          @break
          @case('failed')
          <span class="text-danger"><i class="fa fa-bomb"></i> {{ trans(sprintf('web::contract.%s', $contract->status)) }}</span>
          @break
          @case('finished')
          <span class="text-green"><i class="fa fa-check"></i> {{ trans(sprintf('web::contract.%s', $contract->status)) }}</span>
          @break
          @case('outstanding')
          <span><i class="fa fa-clock-o"></i> {{ trans(sprintf('web::contract.%s', $contract->status)) }}</span>
          @break
          @default
          {{ trans(sprintf('web::contract.%s', $contract->status)) }}
        @endswitch
      </td>
    </tr>
    <tr>
      <th>Title</th>
      <td>{{ $contract->title }}</td>
    </tr>
  </tbody>
</table>

<h4>Delays</h4>

<div class="row">
  <div class="col-md-6">
    <table class="table table-sm table-condensed no-border">
      <tbody>
        <tr>
          <th>Issued</th>
          <td>
            @include('web::partials.date', ['datetime' => $contract->date_issued])
          </td>
        </tr>
        <tr>
          <th>Expires</th>
          <td>
            @include('web::partials.date', ['datetime' => $contract->date_expired])
          </td>
        </tr>
        <tr>
          <th>Accepted</th>
          <td>
            @if(is_null($contract->date_accepted))
              {{ trans('web::contract.not_accepted') }}
            @else
              @include('web::partials.date', ['datetime' => $contract->date_accepted])
            @endif
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col-md-6">
    <table class="table table-condensed no-border">
      <tbody>
      <tr>
        <th>Days to Complete</th>
        <td>
          @if($contract->type == 'courier')
            {{ $contract->days_to_complete ?: 0 }}
          @endif
        </td>
      </tr>
      <tr>
        <th>Days Completed</th>
        <td>
          @if(! is_null($contract->days_completed) && $contract->type == 'courier')
            @include('web::partials.date', ['datetime' => $contract->days_completed])
          @endif
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>

<h4>Financial</h4>

<table class="table table-sm table-condensed no-border">
  <thead>
    <tr>
      @if($contract->type == 'item_exchange')
      <th>Price</th>
      @endif
      <th>Reward</th>
      @if($contract->type == 'courier')
      <th>Collateral</th>
      @endif
      @if($contract->type == 'auction')
      <th>Buyout</th>
      @endif
      <th>Volume</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    @if($contract->type == 'item_exchange')
    <td>{{ number($contract->price) }} ISK</td>
    @endif
    <td>{{ number($contract->reward) }} ISK</td>
    @if($contract->type == 'courier')
    <td>{{ number($contract->collateral) }} ISK</td>
    @endif
    @if($contract->type == 'auction')
    <td>{{ number($contract->buyout) }} ISK</td>
    @endif
    <td>{{ number_metric($contract->volume) }} m3</td>
  </tr>
  </tbody>
</table>

@if($contract->lines->isNotEmpty())
<h4>Content</h4>

<table class="table table-sm table-striped table-condensed">
  <thead>
    <tr>
      <th>Group</th>
      <th>Type</th>
      <th>Quantity</th>
      <th>Volume</th>
    </tr>
  </thead>
  <tbody>
    @foreach($contract->lines as $line)
      <tr>
        <td>{{ $line->type->group->groupName }}</td>
        <td>@include('web::partials.type', ['type_id' => $line->type->typeID, 'type_name' => $line->type->typeName])</td>
        <td>{{ number($line->quantity, 0) }}</td>
        <td>{{ number($line->type->volume * $line->quantity) }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
@endif