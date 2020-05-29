@extends('web::layouts.grids.12')

@section('title', trans('web::seat.standings_builder'))
@section('page_header', trans('web::seat.standings_builder'))
@section('page_description', trans('web::seat.standings_builder'))

@section('full')
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Card Title</h3>
        </div>
        <div class="card-body">
          <p class="text-justify">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
        </div>
        <div class="card-footer">
          <small class="text-muted float-right">Nothing special in this footer</small>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Buttons</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">
              <button class="btn btn-default">Default</button>
            </div>
            <div class="col">
              <button class="btn btn-primary">Primary</button>
            </div>
            <div class="col">
              <button class="btn btn-secondary">Secondary</button>
            </div>
            <div class="col">
              <button class="btn btn-success">Success</button>
            </div>
            <div class="col">
              <button class="btn btn-info">Info</button>
            </div>
            <div class="col">
              <button class="btn btn-warning">Warning</button>
            </div>
            <div class="col">
              <button class="btn btn-danger">Danger</button>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button class="btn btn-default disabled">Default</button>
            </div>
            <div class="col">
              <button class="btn btn-primary disabled">Primary</button>
            </div>
            <div class="col">
              <button class="btn btn-secondary disabled">Secondary</button>
            </div>
            <div class="col">
              <button class="btn btn-success disabled">Success</button>
            </div>
            <div class="col">
              <button class="btn btn-info disabled">Info</button>
            </div>
            <div class="col">
              <button class="btn btn-warning disabled">Warning</button>
            </div>
            <div class="col">
              <button class="btn btn-danger disabled">Danger</button>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button class="btn btn-outline-default">Default</button>
            </div>
            <div class="col">
              <button class="btn btn-outline-primary">Primary</button>
            </div>
            <div class="col">
              <button class="btn btn-outline-secondary">Secondary</button>
            </div>
            <div class="col">
              <button class="btn btn-outline-success">Success</button>
            </div>
            <div class="col">
              <button class="btn btn-outline-info">Info</button>
            </div>
            <div class="col">
              <button class="btn btn-outline-warning">Warning</button>
            </div>
            <div class="col">
              <button class="btn btn-outline-danger">Danger</button>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button class="btn bg-gradient-default">Default</button>
            </div>
            <div class="col">
              <button class="btn bg-gradient-primary">Primary</button>
            </div>
            <div class="col">
              <button class="btn bg-gradient-secondary">Secondary</button>
            </div>
            <div class="col">
              <button class="btn bg-gradient-success">Success</button>
            </div>
            <div class="col">
              <button class="btn bg-gradient-info">Info</button>
            </div>
            <div class="col">
              <button class="btn bg-gradient-warning">Warning</button>
            </div>
            <div class="col">
              <button class="btn bg-gradient-danger">Danger</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Alerts</h3>
        </div>
        <div class="card-body">
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Alert!</h5>
            Danger alert preview. This alert is dismissable. A wonderful serenity has taken possession of my
            entire
            soul, like these sweet mornings of spring which I enjoy with my whole heart.
          </div>
          <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-info"></i> Alert!</h5>
            Info alert preview. This alert is dismissable.
          </div>
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
            Warning alert preview. This alert is dismissable.
          </div>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            Success alert preview. This alert is dismissable.
          </div>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Callouts</h3>
        </div>
        <div class="card-body">
          <div class="callout callout-danger">
            <h5>I am a danger callout!</h5>

            <p>There is a problem that we need to fix. A wonderful serenity has taken possession of my entire
              soul,
              like these sweet mornings of spring which I enjoy with my whole heart.</p>
          </div>
          <div class="callout callout-info">
            <h5>I am an info callout!</h5>

            <p>Follow the steps to continue to payment.</p>
          </div>
          <div class="callout callout-warning">
            <h5>I am a warning callout!</h5>

            <p>This is a yellow callout.</p>
          </div>
          <div class="callout callout-success">
            <h5>I am a success callout!</h5>

            <p>This is a green callout.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Headlines</h3>
        </div>
        <div class="card-body">
          <h1>h1. Bootstrap heading</h1>
          <h2>h1. Bootstrap heading</h2>
          <h3>h1. Bootstrap heading</h3>
          <h4>h1. Bootstrap heading</h4>
          <h5>h1. Bootstrap heading</h5>
          <h6>h1. Bootstrap heading</h6>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Test emphasis</h3>
        </div>
        <div class="card-body">
          <p class="text-success">Text green to emphasize success</p>
          <p class="text-info">Text cyan to emphasize info</p>
          <p class="text-primary">Text blue to emphasize primary</p>
          <p class="text-danger">Text red to emphasize red</p>
          <p class="text-warning">Text orange to emphasize warning</p>
          <p class="text-muted">Text muted to emphasize general</p>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-header">Tables</div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Task</th>
              <th>Progress</th>
              <th style="width: 40px">Label</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>1.</td>
              <td>Update software</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar bg-danger" style="width: 55%"></div>
                </div>
              </td>
              <td><span class="badge bg-danger">55%</span></td>
            </tr>
            <tr>
              <td>2.</td>
              <td>Clean database</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar bg-warning" style="width: 70%"></div>
                </div>
              </td>
              <td><span class="badge bg-warning">70%</span></td>
            </tr>
            <tr>
              <td>3.</td>
              <td>Cron job running</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-primary" style="width: 30%"></div>
                </div>
              </td>
              <td><span class="badge bg-primary">30%</span></td>
            </tr>
            <tr>
              <td>4.</td>
              <td>Fix and squish bugs</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-success" style="width: 90%"></div>
                </div>
              </td>
              <td><span class="badge bg-success">90%</span></td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Simple Full Width Table</h3>

          <div class="card-tools">
            <ul class="pagination pagination-sm float-right">
              <li class="page-item"><a class="page-link" href="#">«</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <table class="table">
            <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Task</th>
              <th>Progress</th>
              <th style="width: 40px">Label</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>1.</td>
              <td>Update software</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                </div>
              </td>
              <td><span class="badge bg-danger">55%</span></td>
            </tr>
            <tr>
              <td>2.</td>
              <td>Clean database</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar bg-warning" style="width: 70%"></div>
                </div>
              </td>
              <td><span class="badge bg-warning">70%</span></td>
            </tr>
            <tr>
              <td>3.</td>
              <td>Cron job running</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-primary" style="width: 30%"></div>
                </div>
              </td>
              <td><span class="badge bg-primary">30%</span></td>
            </tr>
            <tr>
              <td>4.</td>
              <td>Fix and squish bugs</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-success" style="width: 90%"></div>
                </div>
              </td>
              <td><span class="badge bg-success">90%</span></td>
            </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Striped Full Width Table</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <table class="table table-striped">
            <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Task</th>
              <th>Progress</th>
              <th style="width: 40px">Label</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>1.</td>
              <td>Update software</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                </div>
              </td>
              <td><span class="badge bg-danger">55%</span></td>
            </tr>
            <tr>
              <td>2.</td>
              <td>Clean database</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar bg-warning" style="width: 70%"></div>
                </div>
              </td>
              <td><span class="badge bg-warning">70%</span></td>
            </tr>
            <tr>
              <td>3.</td>
              <td>Cron job running</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-primary" style="width: 30%"></div>
                </div>
              </td>
              <td><span class="badge bg-primary">30%</span></td>
            </tr>
            <tr>
              <td>4.</td>
              <td>Fix and squish bugs</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-success" style="width: 90%"></div>
                </div>
              </td>
              <td><span class="badge bg-success">90%</span></td>
            </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Condensed Full Width Table</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <table class="table table-condensed">
            <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Task</th>
              <th>Progress</th>
              <th style="width: 40px">Label</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>1.</td>
              <td>Update software</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                </div>
              </td>
              <td><span class="badge bg-danger">55%</span></td>
            </tr>
            <tr>
              <td>2.</td>
              <td>Clean database</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar bg-warning" style="width: 70%"></div>
                </div>
              </td>
              <td><span class="badge bg-warning">70%</span></td>
            </tr>
            <tr>
              <td>3.</td>
              <td>Cron job running</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-primary" style="width: 30%"></div>
                </div>
              </td>
              <td><span class="badge bg-primary">30%</span></td>
            </tr>
            <tr>
              <td>4.</td>
              <td>Fix and squish bugs</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-success" style="width: 90%"></div>
                </div>
              </td>
              <td><span class="badge bg-success">90%</span></td>
            </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Simple Full Width Table</h3>

          <div class="card-tools">
            <ul class="pagination pagination-sm float-right">
              <li class="page-item"><a class="page-link" href="#">«</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <table class="table">
            <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Task</th>
              <th>Progress</th>
              <th style="width: 40px">Label</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>1.</td>
              <td>Update software</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                </div>
              </td>
              <td><span class="badge bg-danger">55%</span></td>
            </tr>
            <tr>
              <td>2.</td>
              <td>Clean database</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar bg-warning" style="width: 70%"></div>
                </div>
              </td>
              <td><span class="badge bg-warning">70%</span></td>
            </tr>
            <tr>
              <td>3.</td>
              <td>Cron job running</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-primary" style="width: 30%"></div>
                </div>
              </td>
              <td><span class="badge bg-primary">30%</span></td>
            </tr>
            <tr>
              <td>4.</td>
              <td>Fix and squish bugs</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-success" style="width: 90%"></div>
                </div>
              </td>
              <td><span class="badge bg-success">90%</span></td>
            </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Striped Full Width Table</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <table class="table table-striped">
            <thead>
            <tr>
              <th style="width: 10px">#</th>
              <th>Task</th>
              <th>Progress</th>
              <th style="width: 40px">Label</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>1.</td>
              <td>Update software</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                </div>
              </td>
              <td><span class="badge bg-danger">55%</span></td>
            </tr>
            <tr>
              <td>2.</td>
              <td>Clean database</td>
              <td>
                <div class="progress progress-xs">
                  <div class="progress-bar bg-warning" style="width: 70%"></div>
                </div>
              </td>
              <td><span class="badge bg-warning">70%</span></td>
            </tr>
            <tr>
              <td>3.</td>
              <td>Cron job running</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-primary" style="width: 30%"></div>
                </div>
              </td>
              <td><span class="badge bg-primary">30%</span></td>
            </tr>
            <tr>
              <td>4.</td>
              <td>Fix and squish bugs</td>
              <td>
                <div class="progress progress-xs progress-striped active">
                  <div class="progress-bar bg-success" style="width: 90%"></div>
                </div>
              </td>
              <td><span class="badge bg-success">90%</span></td>
            </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Responsive Hover Table</h3>

          <div class="card-tools">
            <div class="input-group input-group-sm" style="width: 150px;">
              <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

              <div class="input-group-append">
                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table table-hover">
            <thead>
            <tr>
              <th>ID</th>
              <th>User</th>
              <th>Date</th>
              <th>Status</th>
              <th>Reason</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>183</td>
              <td>John Doe</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-success">Approved</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>219</td>
              <td>Alexander Pierce</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-warning">Pending</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>657</td>
              <td>Bob Doe</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-primary">Approved</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>175</td>
              <td>Mike Doe</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-danger">Denied</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Fixed Header Table</h3>

          <div class="card-tools">
            <div class="input-group input-group-sm" style="width: 150px;">
              <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

              <div class="input-group-append">
                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0" style="height: 300px;">
          <table class="table table-head-fixed">
            <thead>
            <tr>
              <th>ID</th>
              <th>User</th>
              <th>Date</th>
              <th>Status</th>
              <th>Reason</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>183</td>
              <td>John Doe</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-success">Approved</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>219</td>
              <td>Alexander Pierce</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-warning">Pending</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>657</td>
              <td>Bob Doe</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-primary">Approved</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>175</td>
              <td>Mike Doe</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-danger">Denied</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>134</td>
              <td>Jim Doe</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-success">Approved</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>494</td>
              <td>Victoria Doe</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-warning">Pending</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>832</td>
              <td>Michael Doe</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-primary">Approved</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>982</td>
              <td>Rocky Doe</td>
              <td>11-7-2014</td>
              <td><span class="tag tag-danger">Denied</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>
@endsection