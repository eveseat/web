<h4>Parties</h4>
<table class="table no-border">
  <thead>
    <tr>
      <th>Issuer</th>
      <th>Assignee</th>
      <th>Acceptor</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        @include('web::partials.character', ['character' => $contract->issuer->entity_id])
      </td>
      <td>
        @include('web::partials.character', ['character' => $contract->assignee->entity_id])
      </td>
      <td>
        @include('web::partials.character', ['character' => $contract->acceptor->entity_id])
      </td>
    </tr>
  </tbody>
</table>

<h4>Area</h4>
<table class="table no-border">
  <tbody>
    <tr>
      <th>From Location</th>
      <td>{{ $contract->start_location->name }} - {{ $contract->start_location->system->itemName }} - {{ $contract->start_location->system->region->itemName }}</td>
    </tr>
    <tr>
      <th>End Location</th>
      <td>{{ $contract->end_location->name }} - {{ $contract->end_location->system->itemName }} - {{ $contract->end_location->system->region->itemName }}</td>
    </tr>
  </tbody>
</table>

<h4>Specifications</h4>
<table class="table no-border">
  <tbody>
    <tr>
      <th>Type</th>
      <td></td>
    </tr>
    <tr>
      <th>Status</th>
      <td></td>
    </tr>
    <tr>
      <th>Title</th>
      <td>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</td>
    </tr>
  </tbody>
</table>

<h4>Delays</h4>
<div class="row">
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-6">
        <strong>Issued</strong>
      </div>
      <div class="col-md-6"></div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <strong>Expires</strong>
      </div>
      <div class="col-md-6"></div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <strong>Accepted</strong>
      </div>
      <div class="col-md-6"></div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-6">
        <strong>Days to Complete</strong>
      </div>
      <div class="col-md-6"></div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <strong>Days Completed</strong>
      </div>
      <div class="col-md-6"></div>
    </div>
  </div>
</div>

<h4>Financial</h4>
<table class="table no-border">
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
    <td>{{ number($contract->price) }}</td>
    @endif
    <td>{{ number($contract->reward) }}</td>
    @if($contract->type == 'courier')
    <td>{{ number($contract->collateral) }}</td>
    @endif
    @if($contract->type == 'auction')
    <td>{{ number($contract->buyout) }}</td>
    @endif
    <td>{{ number($contract->volume) }}</td>
  </tr>
  </tbody>
</table>

<div class="row">
  <div class="col-md-6">
    {{-- Parties --}}
    {{-- Issuer : Character + Corporation + Alliance - Row --}}
    {{-- Assignee : Character + Corporation + Alliance - Row --}}
    {{-- Acceptor : Character + Corporation + Alliance - Row --}}

    {{-- Area --}}
    {{-- From : Structure + System + Constellation + Region - Row --}}
    {{-- To : Structure + System + Constellation + Region - Row --}}

    {{-- Metadata --}}
    {{-- Type + Icon --}}
    {{-- Status + Color --}}
    {{-- Title --}}

    {{-- Delay --}}
    {{-- Issued --}}
    {{-- Expired --}}
    {{-- Accepted --}}
    {{-- Days To Complete --}}
    {{-- Days Completed --}}

    {{-- Financial --}}
    {{-- Price --}}
    {{-- Reward --}}
    {{-- Collateral --}}
    {{-- Buyout --}}
    {{-- Volume --}}
  </div>
  <div class="col-md-6">

  </div>
</div>
<div class="row">
  <div class="col-md-6">

  </div>
  <div class="col-md-6">

  </div>
</div>

@if($contract->lines->isNotEmpty())
<h4>Content</h4>
<table class="table">
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