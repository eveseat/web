<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0 bg-primary-lt">
                <h3 class="card-title p-3">
                    <i class="fas fa-code-fork"></i>
                    {{ trans('web::seat.module_versions') }}
                </h3>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item">
                        <a href="#core-packages" data-bs-toggle="pill" aria-expanded="true"
                            class="nav-link active">Core</a>
                    </li>
                    <li class="nav-item">
                        <a href="#plugin-packages" data-bs-toggle="pill" aria-expanded="false"
                            class="nav-link">Plugins</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" data-bs-toggle="tooltip" title="Click to copy packages versions"
                            id="copy-versions" class="nav-link">
                            <i class="fas fa-copy"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div id="core-packages" class="tab-pane active">
                        <dl>

                            @foreach ($packages->core as $package)
                                @include('web::configuration.settings.partials.packages.version')
                            @endforeach

                        </dl>
                    </div>
                    <div id="plugin-packages" class="tab-pane">
                        <dl>

                            @foreach ($packages->plugins as $package)
                                @include('web::configuration.settings.partials.packages.version')
                            @endforeach

                        </dl>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <i class="fas fa-question-circle text-orange"></i> Checking packages (<span
                                id="checking-packages">{{ $packages->core->count() + $packages->plugins->count() }}</span>)
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-check-circle text-green"></i> Updated packages (<span
                                id="updated-packages">0</span>)
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-times-circle text-red"></i> Outdated packages (<span
                                id="outdated-packages">0</span>)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary-lt">
                <h3 class="card-title">{{ trans('web::seat.tp_versions') }}</h3>
            </div>
            <div class="card-body">

                <dl>

                    <dt>
                        <i id="live-sde-status" class="fas fa-question-circle text-orange version-check"
                            data-vendor="ccp" data-name="eve_online_sde"
                            data-version="{{ setting('installed_sde', true) }}" data-bs-toggle="tooltip"
                            title="Checking package status..."></i> Eve Online SDE
                    </dt>
                    <dd>
                        <ul>
                            <li>{{ trans('web::seat.installed') }}: <b>{{ setting('installed_sde', true) }}</b></li>
                            <li id="live-sde-version">{{ trans('web::seat.current') }}: <img
                                    src="https://img.shields.io/badge/version-loading...-blue.svg?style=flat-square">
                            </li>
                            <li>{{ trans('web::seat.url') }}: <a href="https://www.fuzzwork.co.uk/dump"
                                    target="_blank">https://www.fuzzwork.co.uk/dump</a></li>
                        </ul>
                    </dd>

                </dl>

            </div>

        </div>
    </div>
</div>
@include('web::configuration.settings.partials.packages.changelog.modal')

@push('javascript')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {

            var market_prices_region = null;

            window.TomSelect && (market_prices_region = new TomSelect(document.getElementById(
                'market_prices_region'), {
                copyClassesToDropdown: false,
                dropdownClass: 'dropdown-menu',
                optionClass: 'dropdown-item',
                valueField: 'id',
                labelField: 'text',
                searchField: ['text'],
                load: function(query, callback) {
                    var self = this;
                    if (self.loading > 1) {
                        callback();
                        return;
                    }

                    var url = '{{ route('seatcore::fastlookup.regions') }}';
                    fetch(url)
                        .then(response => response.json())
                        .then(json => {
                            callback(json.results);
                            self.settings.load = null;
                        }).catch(() => {
                            callback();
                        });
                },
                render: {
                    option: function(item, escape) {
                        return `<div class="py-2 container w-100">
                <div class="mb-1">
                    <span class="h5">
                        ${ escape(item.text) }
                    </span>
                </div>
                 <div class="ms-auto">${ escape(item.id) }</div>
            </div>`
                    }
                }
            }));

            var url =
                '{{ route('seatcore::fastlookup.regions') }}?_type=find&q={{ setting('market_prices_region_id', true) ?: 0 }}';
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    market_prices_region.addOption(data);
                    market_prices_region.setValue(data.id);
                });

            var installedPackages = {
                "vendor": ["Vendor"],
                "name": ["Package Name"],
                "version": ["Installed Version"]
            };

            // This will update the SDE
            fetch("{{ route('seatcore::check.sde') }}")
                .then(response => response.json())
                .then(data => {
                    var live_sde = "error";
                    if (data != null) {
                        live_sde = data.version;
                    }

                    document.getElementById('live-sde-version').querySelector('img')
                        .setAttribute('src', 'https://img.shields.io/badge/version-' + live_sde.replace(/-/g,
                            '--') + '-blue.svg?style=flat-square');

                    if (live_sde != "error") {
                        var liveSdeStatus = document.getElementById('live-sde-status');
                        var liveSdeOutdated = (live_sde != liveSdeStatus.dataset.version);

                        // remove pending state from the status
                        liveSdeStatus.classList.remove('fa-question-circle');
                        liveSdeStatus.classList.remove('text-orange');

                        // update state according to result
                        liveSdeStatus.classList.add(liveSdeOutdated ? 'fa-times-circle' : 'fa-check-circle');
                        liveSdeStatus.classList.add(liveSdeOutdated ? 'text-red' : 'text-green');
                        liveSdeStatus.setAttribute('title', liveSdeOutdated ?
                            'At least one new version has been released !' : 'The package is up-to-date.');
                        liveSdeStatus.dataset.originalTitle = liveSdeOutdated ?
                            'At least one new version has been released !' : 'The package is up-to-date.';
                    }
                });

            var copyVersions = document.getElementById('copy-versions');

            copyVersions.addEventListener('click', function() {

                var buffer = "```\r\n";

            var colVendorSize = getLongestString(installedPackages.vendor);
            var colNameSize = getLongestString(installedPackages.name);
            var colVersionSize = getLongestString(installedPackages.version);

            // loop over the versions object and build a markdown formatted table
            $.each(installedPackages.vendor, function(index) {
                var row = formatTableRow({
                    "vendor": {
                        "value": installedPackages.vendor[index],
                        "size": colVendorSize
                    },
                    "name": {
                        "value": installedPackages.name[index],
                        "size": colNameSize
                    },
                    "version": {
                        "value": installedPackages.version[index],
                        "size": colVersionSize
                    }
                });

                // append the generated row to the buffer
                buffer += row;

                // in case the generated row is the header, append a dash line of the row length (minus carriage-return
                if (index === 0)
                    buffer += '| ' + padString('', '-', colVendorSize) + ' | ' + padString('',
                        '-', colNameSize) + ' | ' +
                    padString('', '-', colVersionSize) + " |\r\n";
            });

            buffer += "```";

                // Insert the buffer into the clipboard
                navigator.clipboard.writeText(buffer);
                copyVersions.dataset.bsOriginalTitle = 'Copied!';
                let btn_tooltip = bootstrap.Tooltip.getInstance(copyVersions);
                btn_tooltip.show();
            })

            copyVersions.addEventListener('mouseleave', function() {
                copyVersions.dataset.bsOriginalTitle = 'Click to copy packages versions';
            })


            var tochecks = document.querySelectorAll('.version-check');
            for (i = 0; i < tochecks.length; ++i) {
                let toCheckPackage = tochecks[i];
                // fill the packages global variable
                installedPackages.vendor.push(toCheckPackage.dataset.vendor);
                installedPackages.name.push(toCheckPackage.dataset.name);
                installedPackages.version.push(toCheckPackage.dataset.version);

                // skip third party checks
                if (toCheckPackage.dataset.vendor == 'ccp')
                    return false;

                const checkReq = new FormData();
                checkReq.append('vendor', toCheckPackage.dataset.vendor);
                checkReq.append('package', toCheckPackage.dataset.name);
                checkReq.append('version', toCheckPackage.dataset.version);

                fetch('{{ route('seatcore::packages.check') }}', {
                        method: 'POST',
                        body: checkReq,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        // remove pending state from the package
                        toCheckPackage.classList.remove('fa-question-circle');
                        toCheckPackage.classList.remove('text-orange');
                        // update package state according to result
                        toCheckPackage.classList.add(data.outdated ? 'fa-times-circle' : 'fa-check-circle');
                        toCheckPackage.classList.add(data.outdated ? 'text-red' : 'text-green');
                        toCheckPackage.setAttribute('title', data.outdated ?
                            'At least one new version has been released !' :
                            'The package is up-to-date.');
                        toCheckPackage.dataset.originalTitle = data.outdated ?
                            'At least one new version has been released !' :
                            'The package is up-to-date.';

                        // updating counters
                        var checking = document.getElementById('checking-packages');
                        checking.innerText = parseInt(checking.innerText) - 1;
                        if (data.outdated) {
                            var outdated = document.getElementById('outdated-packages');
                            outdated.innerText = parseInt(outdated.innerText) + 1;
                        } else {
                            var updated = document.getElementById('updated-packages');
                            updated.innerText = parseInt(updated.innerText) + 1;
                        }
                    })
            };

            function getLongestString(column) {
                // clone the column in order to keep it unchanged
                var buffer = JSON.parse(JSON.stringify(column));
                // return the longest size
                return buffer.sort(function(a, b) {
                    return b.length - a.length;
                }).shift().length;
            }

            function padString(string, char, size) {
                if (string.length >= size) return string;

                var end = string.length;
                for (i = 0; i < size - end; i++)
                    string += char;

                return string;
            }

            function formatTableRow(row) {
                return '| ' +
                    padString(row.vendor.value, ' ', row.vendor.size) + ' | ' +
                    padString(row.name.value, ' ', row.name.size) + ' | ' +
                    padString(row.version.value, ' ', row.version.size) + " |\r\n";
            }
        });

        var changelogModal = document.getElementById('changelogModal');
        changelogModal.addEventListener('show.bs.modal', function(event) {
            var changelogMetadata = event.relatedTarget;
            var changelogName = changelogMetadata.dataset.name;

            changelogModal.querySelector('.modal-title span').textContent = changelogName;
            changelogModal.querySelector('.modal-body').innerHTML =
                '<div class="overlay dark d-flex justify-content-center align-items-center"><i class="fas fa-2x fa-sync-alt fa-spin"></i>Loading...</div>';



            const form_data = new FormData();
            for (var key in changelogMetadata.dataset) {
                form_data.append(key, changelogMetadata.dataset[key]);
            }
            fetch('{{ route('seatcore::packages.changelog') }}', {
                    method: 'POST',
                    body: form_data,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw Error(response.text);
                    } else {
                        return response.text();
                    }
                })
                .then(html => {
                    changelogModal.querySelector('.modal-body').innerHTML = html;

                    // // load modal content
                    var overlay = changelogModal.querySelector('.overlay');
                    if (overlay !== null) {
                        overlay.parentNode.removeChild(overlay);
                    }
                })
                .catch(function(error) {
                    alert("Error occured. please try again");
                    changelogModal.querySelector('.modal-body').innerHTML = error;
                });
        });
    </script>
@endpush
