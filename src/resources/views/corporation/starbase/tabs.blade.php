<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans_choice('web::seat.starbase', 2) }}</h3>
  </div>
  <div class="panel-body">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active">
        <a href="#starbases"
           role="tab" data-toggle="tab">{{ trans_choice('web::seat.starbase', 2) }}</a>
      </li>
      <li role="presentation">
        <a href="#silos"
           id="silos-tab"
           role="tab" data-toggle="tab" title="Full silo list - warning very slow.">Silos</a>
      </li>
    </ul>
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="starbases">

          @include('web::corporation.starbase.summary')

      </div>
      <div role="tabpanel" class="tab-pane" id="silos" a-ajax-loaded="false">
          <!-- ajax placeholder -->
          test
      </div>
    </div>
  </div>
</div>
