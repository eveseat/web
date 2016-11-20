@if ($supervisor->checkConnection())
  <dl class="dl-horizontal">
    <dt>Version</dt>
    <dd>{{ $supervisor->getSupervisorVersion() }}</dd>
    <dt>RPC Api</dt>
    <dd>{{ $supervisor->getApiVersion() }}</dd>
    <dt>Process ID</dt>
    <dd>{{ $supervisor->getPID() }}</dd>
  </dl>
@endif
