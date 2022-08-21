<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            <div class="ribbon ribbon-start bg-dark">
                <i class="fas fa-balance-scale fa-lg"></i>
            </div>
            <span class="ms-4">@lang('web::about.licences_pane_title')</span>
        </h3>
    </div>
    <div class="card-body">
        <p>@lang('web::about.licences_pane_third_party_used_licences')</p>
        <ul>
            <li>jQuery ~ <a href="https://opensource.org/licenses/mit-license.html"  target="_blank">MIT License</a></li>
            <li>Laravel ~ <a href="https://opensource.org/licenses/mit-license.html"  target="_blank">MIT License</a></li>
            <li>Tabler ~ <a href="https://opensource.org/licenses/mit-license.html"  target="_blank">MIT License</a></li>
            <li>Datatables ~ <a href="https://opensource.org/licenses/mit-license.html"  target="_blank">MIT License</a></li>
            <li>Fontawesome ~ <a href="https://opensource.org/licenses/mit-license.html"  target="_blank">MIT License</a></li>
            <li>JSONPath for PHP ~ <a href="https://opensource.org/licenses/mit-license.html"  target="_blank">MIT License</a></li>
            <li>ESI & EVE Online assets ~ <a href="https://developers.eveonline.com/resource/license-agreement"  target="_blank">@lang('web::about.licences_pane_ccp_third_party_licence')</a></li>
        </ul>
        <p>
            @lang('web::about.licences_pane_seat_used_licence', [
                'licence_link' => '<a href="https://opensource.org/licenses/GPL-2.0"  target="_blank">GNU General Public License (GPL)</a>'
            ])
        </p>
    </div>

</div>