@extends('web::layouts.grids.12')

@section('title', trans('web::seat.about'))
@section('page_header', trans('web::seat.about'))

@section('full')
  <div class="row">
    <div class="col-md-12">

      <div class="card">

        <div class="card-header with-border">
          <h3 class="card-title">
            <i class="fas fa-file-alt"></i>
            Licenses
          </h3>
        </div>
        <div class="card-body">
          <p>SeAT is built upon a large panel of components which are published under the following licenses :</p>
          <ul>
            <li>jQuery ~ <a href="https://opensource.org/licenses/mit-license.html">MIT License</a></li>
            <li>Laravel ~ <a href="https://opensource.org/licenses/mit-license.html">MIT License</a></li>
            <li>Admin LTE ~ <a href="https://opensource.org/licenses/mit-license.html">MIT License</a></li>
            <li>Datatables ~ <a href="https://opensource.org/licenses/mit-license.html">MIT License</a></li>
            <li>Fontawesome ~ <a href="https://opensource.org/licenses/mit-license.html">MIT License</a></li>
            <li>ESI & EVE Online assets ~ <a href="https://developers.eveonline.com/resource/license-agreement">Third Party License Agreement</a></li>
          </ul>
          <p>SeAT himself is published under the <a href="https://opensource.org/licenses/GPL-2.0">GNU General Public License (GPL)</a>.</p>
        </div>

      </div>

    </div>
  </div>

  <div class="row">
    <div class="col-md-12">

      <div class="card box-solid">

        <div class="card-header with-border">
          <h3 class="card-title">
            <i class="fas fa-building"></i>
            CCP Disclaimer
          </h3>
        </div>
        <div class="card-body">
          <p class="text-justify">
            <strong><a href="https://www.eveonline.com" target="_blank">EVE Online</a></strong> and the EVE logo are the
            registered trademarks of <strong><a href="https://www.ccpgames.com" target="_blank">CCP hf</a></strong>.
            All rights are reserved worldwide. All other trademarks are the property of their respective owners.
            EVE Online, the EVE logo, EVE and all associated logos and designs are the intellectual property of CCP hf.
            All artwork, screenshots, characters, vehicles, storylines, world facts or other recognizable features of
            the intellectual property relating to these trademarks are likewise the intellectual property of CCP hf.
            CCP hf. has granted permission to <strong><a href="http://github.com/eveseat/seat/">SeAT</a></strong>
            to use EVE Online and all associated logos and designs for promotional and information purposes on
            its project but does not endorse, and is not in any way affiliated with, SeAT. CCP is in no way responsible
            for the content on or functioning of this software, nor can it be liable for any damage arising from the use
            of this system.
          </p>
        </div>

      </div>

    </div>
  </div>

  <div class="row">

    <div class="col-md-6">

      <div class="card card-solid">

        <div class="card-header with-border">
          <h3 class="card-title">
            <i class="fas fa-comments"></i>
            Contacts
          </h3>
        </div>
        <div class="card-body">
          <p>
            Have a question ? Want to say thank you ? Need to express your opinion on SeAT ?<br/>
            Find us on Slack ! We are on the <a href="https://eve-seat.slack.com">eve-seat</a> Slack team. Get invites <a href="https://eveseat-slack.herokuapp.com">here</a>.
          </p>
          <p>
            Alternatively, you also can track the conversation on the official <a href="https://forums.eveonline.com/default.aspx?g=posts&t=460658&find=unread">EVE Online Forums</a>.
          </p>
          <p>
            Do you have a bug to report ? Please use our Github issue board <a href="https://github.com/eveseat/seat/issues">here</a>.
          </p>
        </div>

      </div>

    </div>

    <div class="col-md-6">

      <div class="card card-solid">

        <div class="card-header with-border">
          <h3 class="card-title">
            <i class="fas fa-user-secret"></i>
            Security Concerns
          </h3>
        </div>
        <div class="card-body">
          <p>
            If you find any security vulnerabilities within SeAT, please send an email to
            <a href="mailto:theninjabag@gmail.com">theninjabag@gmail.com</a> to address instead of creating a public Github issue.
          </p>
        </div>

      </div>

    </div>

  </div>
@stop
