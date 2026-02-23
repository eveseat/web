<h4>About</h4>

<div class="media">
    <!-- TODO LATER: consider icons here -->
    <div class="media-body">
        <h5 class="mt-0">{{ $project->name }}</h5>
        <p class="text-justify">
            {!! $project->description ?: 'No description provided.' !!}
        </p>
    </div>
</div>

<div class="row">
    <div class="col-4">
        <dl>
            <dt>Career</dt>
            <dd>{{ $project->career }}</dd>
        </dl>
    </div>
    <div class="col-4">
        <dl>
            <dt>State</dt>
            <dd>{{ $project->state }}</dd>
        </dl>
    </div>
    <div class="col-4">
        <dl>
            <dt>Created</dt>
            <dd>{{ carbon($project->created)->toDayDateTimeString() }}</dd>
        </dl>
    </div>
</div>

<div class="row mb-3">
    <div class="col-4">
        <dl>
            <dt>Expires</dt>
            <dd>
                @if(!is_null($project->Expires))
                <span data-toggle="tooltip" title="{{ $project->expires }}">
                    @if(carbon()->addDay()->gte($project->expires))
                        <i class="fas fa-exclamation-circle text-danger"></i>
                    @elseif(carbon()->addDays(2)->gte($project->expires))
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                    @else
                        <i class="fas fa-check text-success"></i>
                    @endif
                    {{ human_diff($project->expires) }}
                </span>
                @else
                No Expiry
                @endif
            </dd>
        </dl>
    </div>

    <div class="col-4">
        <dl>
            <dt>Progress</dt>
            <dd>
                {{ number_format($project->progress_current) }}
                /
                {{ number_format($project->progress_desired) }}
            </dd>
        </dl>
    </div>

    <div class="col-4">
        <dl>
            <dt>Creator</dt>
            <dd>
                @include('web::partials.character', ['character' => $project->creator])
            </dd>
        </dl>
    </div>
</div>

<h4>Financial</h4>

<div class="row mb-3">
    <div class="col-4">
        <dl>
            <dt>Initial Reward</dt>
            <dd>{{ number_format($project->reward_initial) }} ISK</dd>
        </dl>
    </div>
    <div class="col-4">
        <dl>
            <dt>Remaining Reward</dt>
            <dd>{{ number_format($project->reward_remaining) }} ISK</dd>
        </dl>
    </div>
    <div class="col-4">
        <dl>
            <dt>Reward per Contribution</dt>
            <dd>{{ number_format($project->contribution_reward) }} ISK</dd>
        </dl>
    </div>
</div>

<h4>Contribution Rules</h4>

<div class="row mb-3">
    <div class="col-4">
        <dl>
            <dt>Participation Limit</dt>
            <dd>
                {{ $project->contribution_participation_limit ?? 'Unlimited' }}
            </dd>
        </dl>
    </div>

    <div class="col-4">
        <dl>
            <dt>Submission Limit</dt>
            <dd>
                {{ $project->contribution_submission_limit ?? 'Unlimited' }}
            </dd>
        </dl>
    </div>

    <div class="col-4">
        <dl>
            <dt>Submission Multiplier</dt>
            <dd>
                {{ $project->contribution_submission_multiplier ?? 'None' }}
            </dd>
        </dl>
    </div>
</div>

<h4>Contributors</h4>

<table class="table table-sm table-striped">
    <thead>
        <tr>
            <th>Character</th>
            <th>Contributed</th>
            <th>Last Updated</th>
        </tr>
    </thead>
    <tbody>
        @forelse($project->contributors as $contributor)
            <tr>
                <td>
                    @include('web::partials.character', ['character' => $contributor->character])
                </td>
                <td>{{ number_format($contributor->contributed) }}</td>
                <td>{{ carbon($contributor->updated_at)->toDayDateTimeString() }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">No contributions yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<h4>Configuration</h4>


@php
// TODO need a better place for this than here... Dont like PHP in blade
    if (!isset($config) || !is_array($config) || empty($config)) {
        $config = [];
    }

    $entry = $config[0] ?? null;

    if ($entry) {
        $topKey = $entry['key'] ?? 'configuration';
        $prettyTopKey = ucwords(str_replace('_', ' ', $topKey));

        $decoded = $entry['value'] ?? '{}';
        $decoded = is_string($decoded) ? json_decode($decoded, true) : $decoded;

        if (!is_array($decoded)) {
            $decoded = [];
        }

        // Render values nicely
        $renderValue = function ($value) {
          // Null
          if (is_null($value)) {
              return '<span class="text-muted">null</span>';
          }

          // Boolean
          if (is_bool($value)) {
              return $value ? 'true' : 'false';
          }

          // Array of objects or arrays → render each entry on its own line
          if (is_array($value)) {
              // Array of associative arrays (objects)
              if (isset($value[0]) && is_array($value[0])) {
                  $html = '';
                  foreach ($value as $item) {
                      $html .= '<div><pre class="mb-1" style="white-space:pre-wrap;">'
                          . e(json_encode($item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))
                          . '</pre></div>';
                  }
                  return $html;
              }

              // Simple array → each item on its own line
              $html = '';
              foreach ($value as $item) {
                  $html .= '<div>' . e((string) $item) . '</div>';
              }
              return $html;
          }

          // Long strings → scrollable block
          if (is_string($value) && strlen($value) > 120) {
              return '<pre class="mb-0" style="white-space:pre-wrap;max-height:8rem;overflow:auto;">'
                  . e($value)
                  . '</pre>';
          }

          // Scalar
          return e((string) $value);
      };
    }
@endphp

@if(empty($config))
    <p class="text-muted">No configuration available for this project.</p>
@else
    <h5 class="mt-3 mb-2">{{ $prettyTopKey }}</h5>

    <div class="table-responsive mb-3">
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th style="width: 25%;">Setting</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($decoded as $key => $value)
                    @php
                        $prettyKey = ucwords(str_replace('_', ' ', $key));
                    @endphp

                    <tr>
                        <td class="align-middle">
                            <code>{{ $prettyKey }}</code>
                        </td>
                        <td class="align-middle">
                            {!! $renderValue($value) !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
