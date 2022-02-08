<div class="row row-cards mt-0 me-0 ms-0 pe-0 ps-0">

    <div class="col-md-4 col-sm-6">

        <!-- Characters Badge -->
        <x-seat::widgets.badge icon="fas fa-key" color="bg-info" title="{{ trans('web::seat.linked_characters') }}" value="0" id="badge-character-count" data-url="{{ route('seatcore::meters.characters.count', ['character' => auth()->user()->main_character_id]) }}" />

    </div>

    <div class="col-md-4 col-sm-6">

        <!-- Skills Badge -->
        <x-seat::widgets.badge icon="fas fa-graduation-cap" color="bg-black" title="{{ trans('web::seat.total_character_skillpoints') }}" value="0" id="badge-character-skillpoints" data-url="{{ route('seatcore::meters.characters.skillpoints', ['character' => auth()->user()->main_character_id]) }}" />

    </div>

    <div class="col-md-4 col-sm-6">

        <!-- Wallet Badge -->
        <x-seat::widgets.badge icon="far fa-money-bill-alt" color="bg-blue" title="{{ trans('web::seat.total_character_isk') }}" value="0" id="badge-character-balances" data-url="{{ route('seatcore::meters.characters.balances', ['character' => auth()->user()->main_character_id]) }}" />

    </div>

    <div class="col-md-4 col-sm-6">

        <!-- Ore Badge -->
        <x-seat::widgets.badge icon="far fa-gem" color="bg-purple" title="{{ trans('web::seat.total_character_mined_isk') }}" value="0" id="badge-character-mining" data-url="{{ route('seatcore::meters.characters.mining', ['character' => auth()->user()->main_character_id]) }}" />

    </div>

    <div class="col-md-4 col-sm-6">

        <!-- NPC Badge -->
        <x-seat::widgets.badge icon="fas fa-coins" color="bg-yellow" title="{{ trans('web::seat.total_character_ratted_isk') }}" value="0" id="badge-character-ratting" data-url="{{ route('seatcore::meters.characters.rating', ['character' => auth()->user()->main_character_id]) }}" />

    </div>

    <div class="col-md-4 col-sm-6">

        <!-- Kills Badge -->
        <x-seat::widgets.badge icon="fas fa-space-shuttle" color="bg-red" title="{{ trans('web::seat.total_killmails') }}" value="0" id="badge-character-kills" data-url="{{ route('seatcore::meters.characters.kills', ['character' => auth()->user()->main_character_id]) }}" />

    </div>

    <div class="col-4">
        <div class="card h-100">
            <div class="card-body">
                <div id="skills-level-chart" data-url="{{ route('seatcore::character.view.skills.graph.level', ['character' => auth()->user()->main_character_id]) }}"></div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card h-100">
            <div class="card-body">
                <div id="training-profile-chart" data-url="{{ route('seatcore::character.view.skills.graph.profile', ['character' => auth()->user()->main_character_id]) }}"></div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card h-100">
            <div class="card-body">
                <div id="training-queue-chart" data-url="{{ route('seatcore::character.view.skills.graph.queue', ['character' => auth()->user()->main_character_id]) }}"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div id="skills-completeness-chart" data-url="{{ route('seatcore::character.view.skills.graph.coverage', ['character' => auth()->user()->main_character_id]) }}"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-body">
                <div id="wallet-earnings-chart" data-url="{{ route('seatcore::character.view.wallet.graph.earnings', ['character' => auth()->user()->main_character_id]) }}"></div>
            </div>
        </div>
    </div>

</div>

@push('javascript')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var currencyFormatter = Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'ISK'
        });

        // character count badge
        let characterCountBadgeNode = document.querySelector('#badge-character-count');

        // character skills badge
        let characterSkillsBadgeNode = document.querySelector('#badge-character-skillpoints');

        // character balances badge
        let characterBalancesBadge = document.querySelector('#badge-character-balances');

        // character mining badge
        let characterMiningBadge = document.querySelector('#badge-character-mining');

        // character ratting badge
        let characterRattingBadge = document.querySelector('#badge-character-ratting');

        // character kills badge
        let characterKillsBadge = document.querySelector('#badge-character-kills');

        // skill per level chart
        let skillsLevelNode = document.querySelector('#skills-level-chart');
        let skillsLevelChart = new ApexCharts(skillsLevelNode, {
            xaxis: {
                categories: ['Level 0', 'Level I', 'Level II', 'Level III', 'Level IV', 'Level V']
            },
            colors: ['#626976', '#d63939', '#f59f00', '#2fb344', '#206bc4', '#4299e1'],
            series: [],
            plotOptions: {
                bar: {
                    distributed: true
                }
            },
            chart: {
                type: 'bar',
                height: 250,
                toolbar: {
                    show: false
                }
            },
            tooltip: {
                enabled: false
            },
            noData: {
                text: 'Loading...'
            }
        });
        skillsLevelChart.render();

        // training profile chart
        let trainingProfileNode = document.querySelector('#training-profile-chart');
        let trainingProfileChart = new ApexCharts(trainingProfileNode, {
            labels: ['Core', 'Leadership', 'Fighter', 'Industrial'],
            colors: ['#f59f00', '#74b816', '#d63939', '#4263eb'],
            series: [],
            chart: {
                type: 'donut',
                height: 250,
                toolbar: {
                    show: false
                }
            },
            tooltip: {
                enabled: false
            },
            noData: {
                text: 'Loading...'
            }
        });
        trainingProfileChart.render();

        let trainingQueueNode = document.querySelector('#training-queue-chart');
        let trainingQueueChart = new ApexCharts(trainingQueueNode, {
            series: [],
            chart: {
                height: 250,
                type: 'rangeBar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true
                }
            },
            xaxis: {
                type: 'datetime',
            }
        });
        trainingQueueChart.render();

        // skill completeness chart
        let skillCompletenessNode = document.querySelector('#skills-completeness-chart');
        let skillCompletenessChart = new ApexCharts(skillCompletenessNode, {
            series: [],
            xaxis: {
                categories: ['Armor', 'Corporation Management', 'Drones', 'Electronic Systems', 'Engineering', 'Fleet Support', 'Gunnery', 'Missiles', 'Navigation', 'Neural Enhancement', 'Planet Management', 'Production', 'Resource Processing', 'Rigging', 'Scanning', 'Science', 'Shields', 'Social', 'Spaceship Command', 'Structure Management', 'Subsystems', 'Targeting', 'Trade']
            },
            yaxis: {
                min: 0.0,
                max: 100.0
            },
            chart: {
                type: 'radar',
                height: 600,
                toolbar: {
                    show: false
                }
            },
            grid: {
                padding: {
                    top: -50,
                    right: -50,
                    bottom: -50,
                    left: -50
                }
            },
            noData: {
                text: 'Loading...'
            }
        });
        skillCompletenessChart.render();

        // wallet earnings chart
        let walletEarningsNode = document.querySelector('#wallet-earnings-chart');
        let walletEarningsChart = new ApexCharts(walletEarningsNode, {
            series: [],
            chart: {
                type: 'bar',
                height: 600,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    colors: {
                        ranges: [{
                            from: 0,
                            to: 999999999999,
                            color: '#74b816'
                        }, {
                            from: -999999999999,
                            to: 0,
                            color: '#d63939'
                        }]
                    },
                    columnWidth: '80%',
                }
            },
            dataLabels: {
                enabled: false,
            },
            yaxis: {
                title: {
                    text: 'Growth',
                },
                labels: {
                    formatter: function (y) {
                        //return y.toFixed(0) + "%";
                        return currencyFormatter.format(y);
                    }
                }
            },
            xaxis: {
                type: 'datetime',
                categories: [],
                labels: {
                    rotate: -90
                }
            },
            noData: {
                text: 'Loading...'
            }
        });
        walletEarningsChart.render();

        [characterCountBadgeNode, characterSkillsBadgeNode, characterBalancesBadge, characterMiningBadge, characterRattingBadge, characterSkillsBadgeNode].forEach(badge => {
            fetch(badge.dataset.url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': document.head.querySelector('[name=csrf-token][content]').content
                }
            }).then(response => {
                return response.json();
            }).then(data => {
                badge.querySelector('.text-muted.fst-italic').firstChild.textContent = data;
            });
        });

        fetch(skillsLevelNode.dataset.url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.head.querySelector('[name=csrf-token][content]').content
            },
        }).then(response => {
            return response.json();
        }).then(data => {
            skillsLevelChart.updateSeries([
                {
                    data: data.datasets[0].data
                }
            ]);
        }).catch(function(error) {
            console.error(error);
        });

        fetch(trainingProfileNode.dataset.url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.head.querySelector('[name=csrf-token][content]').content
            },
        }).then(response => {
            return response.json();
        }).then(data => {
            trainingProfileChart.updateSeries([
                data.core.stats,
                data.leadership.stats,
                data.fighter.stats,
                data.industrial.stats,
            ]);
        }).catch(function(error) {
            console.error(error);
        });

        fetch(trainingQueueNode.dataset.url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.head.querySelector('[name=csrf-token][content]').content
            },
        }).then(response => {
            return response.json();
        }).then(data => {
            trainingQueueChart.updateSeries([
                {
                    data: data
                }
            ]);
        }).catch(function(error) {
            console.error(error);
        });

        fetch(skillCompletenessNode.dataset.url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.head.querySelector('[name=csrf-token][content]').content
            },
        }).then(response => {
            return response.json();
        }).then(data => {
            skillCompletenessChart.updateSeries([
                {
                    name: 'Overall',
                    data: data.datasets[0].data
                }
            ]);
        }).catch(function(error) {
            console.error(error);
        });

        fetch(walletEarningsNode.dataset.url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': document.head.querySelector('[name=csrf-token][content]').content
            },
        }).then(response => {
            return response.json();
        }).then(data => {
            walletEarningsChart.updateOptions({
                xaxis: {
                    categories: data.datasets[0].categories
                }
            })
            walletEarningsChart.updateSeries([
                {
                    name: 'Cash Flow',
                    data: data.datasets[0].data
                }
            ]);
        }).catch(function(error) {
            console.error(error);
        });
    </script>
@endpush
