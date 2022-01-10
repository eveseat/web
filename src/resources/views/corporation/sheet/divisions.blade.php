<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ trans('web::seat.divisional_information') }}</h3>
        <div class="card-tools">
            <div class="input-group input-group-sm">
                @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.divisions', 'label' => trans('web::seat.update_divisions')])
            </div>
        </div>
    </div>
    <div class="card-body">

        <dl>
            <dt><i class="fas fa-cubes"></i> {{ trans('web::seat.corporation_divisions') }}</dt>
            <dd>
                <ol>
                    @foreach($asset_divisions as $asset_division)
                        @switch(true)
                            @case($asset_division->division == 1 && \Illuminate\Support\Facades\Gate::allows('corporation.asset_first_division', $corporation))
                            @case($asset_division->division == 2 && \Illuminate\Support\Facades\Gate::allows('corporation.asset_second_division', $corporation))
                            @case($asset_division->division == 3 && \Illuminate\Support\Facades\Gate::allows('corporation.asset_third_division', $corporation))
                            @case($asset_division->division == 4 && \Illuminate\Support\Facades\Gate::allows('corporation.asset_fourth_division', $corporation))
                            @case($asset_division->division == 5 && \Illuminate\Support\Facades\Gate::allows('corporation.asset_fifth_division', $corporation))
                            @case($asset_division->division == 6 && \Illuminate\Support\Facades\Gate::allows('corporation.asset_sixth_division', $corporation))
                            @case($asset_division->division == 7 && \Illuminate\Support\Facades\Gate::allows('corporation.asset_seventh_division', $corporation))
                            <li>{{ $asset_division->name }}</li>
                            @break
                        @endswitch
                    @endforeach
                </ol>
            </dd>
        </dl>

        <dl>
            <dt><i class="far fa-money-bill-alt"></i> {{ trans('web::seat.wallet_divisions') }}</dt>
            <dd>
                <ol>
                    @foreach($wallet_divisions as $wallet_division)
                        @switch(true)
                            @case($wallet_division->division == 1 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_first_division', $corporation))
                            @case($wallet_division->division == 2 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_second_division', $corporation))
                            @case($wallet_division->division == 3 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_third_division', $corporation))
                            @case($wallet_division->division == 4 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_fourth_division', $corporation))
                            @case($wallet_division->division == 5 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_fifth_division', $corporation))
                            @case($wallet_division->division == 6 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_sixth_division', $corporation))
                            @case($wallet_division->division == 7 && \Illuminate\Support\Facades\Gate::allows('corporation.wallet_seventh_division', $corporation))
                            @if(is_null($wallet_division->name))
                                <li>Master</li>
                            @else
                                <li>{{ $wallet_division->name }}</li>
                            @endif
                            @break
                        @endswitch
                    @endforeach
                </ol>
            </dd>
        </dl>

    </div>
</div>