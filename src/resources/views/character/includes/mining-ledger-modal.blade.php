<div class="modal fade" id="detailed-ledger" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    {!! img('type', 0, 32, ['class' => 'img-circle eve-icon'], false) !!} Detailed Ledger for
                    <span id="modal-ledger-system-name">{system_name}</span> on <span id="modal-ledger-date">{date}</span> -
                    <span id="modal-ledger-type-name">{type_name}</span>
                </h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed table-striped" id="hourly-ledger">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Quantity</th>
                            <th>Volume</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
