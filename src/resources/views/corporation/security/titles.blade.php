@extends('web::layouts.corporation', ['viewname' => 'titles', 'breadcrumb' => trans_choice('web::seat.title', 2)])

@section('page_description', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.title', 2))

@section('corporation_content')

  <div class="d-flex align-items-start">
    <ul class="nav nav-pills seat-nav-vertical-pills flex-column col-2 me-3" role="tablist" aria-orientation="vertical">
      @foreach($corporation->titles as $title)
        <li class="nav-item" role="presentation">
          <a href="#" @class(['nav-link', 'pt-3', 'pb-3', 'active' => $loop->first]) data-bs-toggle="pill" data-bs-target="#title-{{ $title->title_id }}-pane" type="button" role="tab" aria-selected="true">{{ strip_tags($title->name) }}</a>
        </li>
      @endforeach
    </ul>
    <div class="tab-content flex-fill">
      @foreach($corporation->titles as $title)
        <div @class(['tab-pane', 'fade', 'show' => $loop->first, 'active' => $loop->first]) id="title-{{ $title->title_id }}-pane" role="tabpanel">
          <div class="card">
            <nav class="nav nav-pills d-flex justify-content-between border-bottom" id="title-{{ $title->title_id }}-scrollnav">
              <a href="#title-{{ $title->title_id }}-members" class="nav-link pt-3 pb-3 active">Members</a>
              <a href="#title-{{ $title->title_id }}-general" class="nav-link pt-3 pb-3">General</a>
              <a href="#title-{{ $title->title_id }}-station-service" class="nav-link pt-3 pb-3">Station Service</a>
              <a href="#title-{{ $title->title_id }}-accounting-divisional" class="nav-link pt-3 pb-3">Accounting Divisional</a>
              <a href="#title-{{ $title->title_id }}-hangar-access-hq" class="nav-link pt-3 pb-3">Hangar Access (HQ)</a>
              <a href="#title-{{ $title->title_id }}-container-access-hq" class="nav-link pt-3 pb-3">Container Access (HQ)</a>
              <a href="#title-{{ $title->title_id }}-hangar-access-base" class="nav-link pt-3 pb-3">Hangar Access (Base)</a>
              <a href="#title-{{ $title->title_id }}-container-access-base" class="nav-link pt-3 pb-3">Container Access (Base)</a>
              <a href="#title-{{ $title->title_id }}-hangar-access-other" class="nav-link pt-3 pb-3">Hangar Access (Other)</a>
              <a href="#title-{{ $title->title_id }}-container-access-other" class="nav-link pt-3 pb-3">Container Access (Other)</a>
            </nav>
            <div class="card-body overflow-auto" style="max-height: 800px" data-bs-spy="scroll" data-bs-target="#title-{{ $title->title_id }}-scrollnav" data-bs-offset="100" tabindex="0">

              {{-- Members --}}

              <h3 class="display-6 mb-3" id="title-{{ $title->title_id }}-members">Members</h3>

              <div class="row">
                @foreach($title->characters as $character)
                  <div class="col-2 mb-3">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        {!! img('characters', 'portrait', $character->character_id, 64, ['class' => 'avatar'], false) !!}
                      </div>
                      <div class="col">
                        <a href="#" class="text-body d-block">{{ $character->name }}</a>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              {{-- General --}}

              <h3 class="display-6 mt-5 mb-3" id="title-{{ $title->title_id }}-general">General</h3>

              <ul class="list-inline d-flex justify-content-between mb-4">
                @foreach(['Accountant', 'Auditor', 'Communications_Officer', 'Config_Equipment', 'Config_Starbase_Equipment', 'Contract_Manager', 'Diplomat', 'Fitting_Manager', 'Junior_Accountant', 'Personnel_Manager', 'Skill_Plan_Manager','Starbase_Defense_Operator', 'Starbase_Fuel_Technician'] as $role)
                  <li class="list-inline-item">
                    <span @class([
                        'avatar',
                        'bg-success' => $title->roles->where('type', 'grantable_roles')->where('role', $role)->isNotEmpty(),
                        'bg-warning' => $title->roles->where('type', 'grantable_roles')->where('role', $role)->isEmpty() && $title->roles->where('type', 'roles')->where('role', $role)->isNotEmpty(),
                        'bg-muted' => $title->roles->where('role', $role)->isEmpty(),
                    ])></span>
                  </li>
                @endforeach
              </ul>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Accountant', 'role_type' => 'roles'])
                    Accountant
                  </div>
                  <div>
                  Read access to all corporation wallet logs and the full corporation asset listing, as well as the ability to pay bills. Also has full access to the "Corporation Deliveries" section of the inventory.

                  The role does NOT grant take access to corporation wallets, this will have to be assigned separately.
                  </div>
                </div>
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Fitting_Manager', 'role_type' => 'roles'])
                    Fitting Manager
                  </div>
                  <div>Can fully manage corporation fittings</div>
                </div>
              </div>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Auditor', 'role_type' => 'roles'])
                    Auditor
                  </div>
                  Can access the "Auditing" tab within the "Members" section of the corporation management to review role assignments and removals from corporation members
                </div>
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Junior_Accountant', 'role_type' => 'roles'])
                    Junior Accountant
                  </div>
                  Read-only access to all corporation wallet division balances, bills, and to the "Corporation Deliveries" section of the inventory.
                </div>
              </div>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Communications_Officer', 'role_type' => 'roles'])
                    Communication Officer
                  </div>
                  Allows setting a message of the Day within the corporation chat channel (also alliance chat channel where applicable)
                </div>
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Personnel_Manager', 'role_type' => 'roles'])
                    Personnel Manager
                  </div>
                  Can accept applications from other players to the corporation.
                </div>
              </div>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Config_Equipment', 'role_type' => 'roles'])
                    Config Equipment
                  </div>
                  Can deploy and configure Containers and all deployables except Starbases, Upwell Structures and sovereignty structures in space in the name of the corporation
                </div>
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Skill_Plan_Manager', 'role_type' => 'roles'])
                    Skill Plan Manager
                  </div>
                </div>
              </div>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Config_Starbase_Equipment', 'role_type' => 'roles'])
                    Config Starbase Equipment
                  </div>
                  Can deploy and configure Starbases and sovereignty structures in space in the name of the corporation. Does not apply to deploying Upwell Stuctures, which require the "Station Manager" role to be deployed.
                </div>
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Starbase_Defense_Operator', 'role_type' => 'roles'])
                    Starbase Defense Operator
                  </div>
                  Can take control of Starbase Defenses (Weapon Batteries etc.) of Starbases owned by the corporation. Not required to take control over Upwell Structure defenses, that access is regulated by the structures profile.
                </div>
              </div>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Contract_Manager', 'role_type' => 'roles'])
                    Contract Manager
                  </div>
                  Can create or accept contracts in the name of the corporation using the corporation wallet for any fees or other costs/profits that may apply. This role is NOT required to accept contracts that were assigned to the corporation, which can be accepted by each member of the corporation in their own name (paying all fees out of their own wallets).

                  This role does not grant access to the Corporation Deliveries section, which would receive items from contracts that were accepted on behalf of the corporation. The Accountant or Trader role is required as well in order to remove items from Corporation Deliveries.
                </div>
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Starbase_Fuel_Technicien', 'role_type' => 'roles'])
                    Starbase Fuel Technicien
                  </div>
                  Access to the fuel bays and silos of corporation owned Starbases. Not required for Fuel/Fighter/Ammo Bay access of Upwell structures, as that is access is regulated by the structures profile.
                </div>
              </div>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Diplomat', 'role_type' => 'roles'])
                    Diplomat
                  </div>
                  Can fully manage the corporation contact list
                </div>
              </div>

              {{-- Station Service --}}

              <h3 class="display-6 mt-5 mb-3" id="title-{{ $title->title_id }}-station-service">Station Service</h3>

              <ul class="list-inline d-flex justify-content-between mb-4">
                @foreach(['Factory_Manager', 'Rent_Factory_Facility', 'Rent_Office', 'Rent_Research_Facility', 'Security_Officer', 'Station_Manager', 'Trader'] as $role)
                  <li class="list-inline-item">
                    <span @class([
                        'avatar',
                        'bg-success' => $title->roles->where('type', 'grantable_roles')->where('role', $role)->isNotEmpty(),
                        'bg-warning' => $title->roles->where('type', 'grantable_roles')->where('role', $role)->isEmpty() && $title->roles->where('type', 'roles')->where('role', $role)->isNotEmpty(),
                        'bg-muted' => $title->roles->where('role', $role)->isEmpty(),
                    ])></span>
                  </li>
                @endforeach
              </ul>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Factory_Manager', 'role_type' => 'roles'])
                    Factory Manager
                  </div>
                </div>
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Security_Officer', 'role_type' => 'roles'])
                    Security Officer
                  </div>
                </div>
              </div>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Rent_Factory_Facility', 'role_type' => 'roles'])
                    Rent Factory Facility
                  </div>
                </div>
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Station_Manager', 'role_type' => 'roles'])
                    Station Manager
                  </div>
                </div>
              </div>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Rent_Office', 'role_type' => 'roles'])
                    Rent Office
                  </div>
                </div>
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Trader', 'role_type' => 'roles'])
                    Trader
                  </div>
                </div>
              </div>

              <div class="row row-cols-2 ps-3 pe-3 mt-3">
                <div class="col ps-3 pe-3">
                  <div class="font-weight-bold mb-2">
                    @include('web::corporation.security.partials.role-checkbox', ['role_name' => 'Rent_Research_Facility', 'role_type' => 'roles'])
                    Rent Research Facility
                  </div>
                </div>
              </div>

              {{-- Accounting Divisional --}}

              @include('web::corporation.security.partials.accounting-divisional', ['section_label' => 'Accounting Divisional'])

              {{-- Hangar Access (HQ) --}}

              @include('web::corporation.security.partials.hangar-access', ['section_label' => 'Hangar Access (HQ)', 'role_type' => 'hq'])

              {{-- Container Access (HQ) --}}

              @include('web::corporation.security.partials.container-access', ['section_label' => 'Container Access (HQ)', 'role_type' => 'hq'])

              {{-- Hangar Access (Base) --}}

              @include('web::corporation.security.partials.hangar-access', ['section_label' => 'Hangar Access (Base)', 'role_type' => 'base'])

              {{-- Container Access (Base) --}}

              @include('web::corporation.security.partials.container-access', ['section_label' => 'Container Access (Base)', 'role_type' => 'base'])

              {{-- Hangar Access (Other) --}}

              @include('web::corporation.security.partials.hangar-access', ['section_label' => 'Hangar Access (Other)', 'role_type' => 'other'])

              {{-- Container Access (Other) --}}

              @include('web::corporation.security.partials.container-access', ['section_label' => 'Container Access (Other)', 'role_type' => 'other'])

            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

@stop

@push('head')
  <style>
    .seat-nav-vertical-pills {
      box-shadow: rgb(30 41 59 / 4%) 0 2px 4px 0;
      border: 1px solid rgba(98,105,118,.16);
      background: var(--tblr-card-bg, #fff);
    }

    .seat-nav-vertical-pills li {
      position: relative;
    }

    .seat-nav-vertical-pills li .active::after {
      position: absolute;
      content: "";
      top: 0;
      right: -1px;
      bottom: 0;
      border-right: 1px solid var(--tblr-blue);
    }
  </style>
@endpush