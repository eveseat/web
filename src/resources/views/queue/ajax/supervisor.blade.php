@if ($supervisor->checkConnection())
<dl>
  <dt>Version</dt>
  <dd>{{ $supervisor->getSupervisorVersion() }}</dd>
  <dt>RPC Api</dt>
  <dd>{{ $supervisor->getApiVersion() }}</dd>
  <dt>Process ID</dt>
  <dd>{{ $supervisor->getPID() }}</dd>
</dl>
@endif