@extends('web::layouts.grids.12')

@section('title', trans_choice('web::squads.squad', 0))
@section('page_header', trans_choice('web::squads.squad', 1))
@section('page_description', 'Create')

@section('full')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="card-actions">
            <div class="input-group input-group-sm">
              @include('web::components.filters.buttons.filters', ['rules' => '{}'])
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAYAAADDPmHLAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAErklEQVR4nO3aTY7aQBRF4dNRJDbDjP0vwbNeB3OPOoOoOrSD7bJdP++9uncIJLJ8Pgw4+Xg+n19ow+4XwDzPvY9D67RfALfbTQgG3DzPfwGAEIy2eZ653W7/AIAQjLIUH/gJAIQg+l7jwxsAIARRt4wPKwBACKLtXXzYAABCEGVr8WEHAAiB923FhwwAIARetxcfMgGAEHhbTnw4AACEwMty48NBACAE1nckPpwAAEJgdUfjw0kAIATWdiY+XAAAQmBlZ+PDRQAgBL13JT4UAABC0GtX40MhACAErVciPhQEAELQaqXiQ2EAIAS1VzI+VAAAQlBrpeNDJQAgBKVXIz5UBABCUGq14kNlACAEV1czPjQAAEJwdrXjQyMAIARH1yI+NAQAQpC7VvGhMQAQgr21jA8dAIAQrK11fOgEAIRguR7xoSMAEIK0XvGhMwAQgp7xwQAAGBdB7/hgBACMh8BCfDAEAMZBYCU+GAMA8RFYig8GAUBcBNbig1EAEA+BxfhgGADEQWA1PhgHAP4RWI4PDgCAXwTW44MTAOAPgYf44AgA+EHgJT44AwD2EXiKDw4BgF0E3uKDUwBgD4HH+OAYANhB4DU+OAcA/RF4jg8BAEA/BN7jQxAA0B5BhPgQCAC0QxAlPgQDAPURRIoPAQFAPQTR4kNQAFAeQcT4EBgAlEMQNT4EBwDXEUSODwMAgPMIoseHQQDAcQQjxAf43fsAXjdNEwCPx+Pt42mvz289t1xCsBd27zW1j7PlzFwBlido+fjj8fg+aemxrefWtnclyI1f+zhbzQSAaZqaviM+Pz9/BJimiWmasuJbeeeWmomPgK2Tuvdcipfz+uWWwfc+GnodZ82ZuALk7PUELi+jRy+t6bWvV4JSQUoeZ4uZuALsrXSkWvNynK8zfwWocVLT33m/34u9Iz3GB/h4Pp9fvQ9i7eSnz86159792ZwAa5fo+/1+6hdAreNsMRMAWm/r2/4oN4DSzH8ElN5e4N7/x7D1hgKQ++4eCcEwAI5e2kdBMASAs5/rIyAID+Dql7roCEIDKPWNPjKCsABK/5yLiiAkgFq/5SMiCAeg9o2caAhCAWh1Fy8SgjAAWt/CjYIgBIBe9+8jIHAPoPc/3nhH4BpA7/hpnhG4BWAlfppXBC4BWIuf5hGBOwBW46d5Q+AKgPX4aZ4QuAHgJX6aFwQuAHiLn+YBgXkAXuOnWUdgGoD3+GmWEZgFECV+mlUEJgFEi59mEYE5AFHjp1lDYApA9PhplhCYATBK/DQrCEwAGC1+mgUE3QGMGj+tN4KuAEaPn9YTQTcAiv9zvRB0AaD479cDQXMAir+91giaAlD8vLVE0AyA4h9bKwRNACj+ubVAUB2A4l9bbQRVASh+mdVEUA2A4pddLQRVACh+ndVAUByA4tddaQRFASh+m5VEUAyA4rddKQRFACh+n5VAcBmA4vfdVQSXACi+jV1BcBqA4tvaWQSnACi+zZ1BcBiA4tveUQSHACi+jx1BkA1A8X0tF0EWAMX3uRwEuwAU3/f2EGwCUPwY20KwCkDxY20NwVsAih9z7xD8B0DxY2+J4AcAxR9jrwi+ASj+WEsI/gDX1Xk9/t+gEwAAAABJRU5ErkJggg==" width="128" height="128" class="border align-self-center mr-3" id="squad-logo" />
            </div>
            <div class="flex-grow-1 ms-3">
              <form method="post" action="{{ route('seatcore::squads.store') }}" enctype="multipart/form-data" id="squad-form">
                {!! csrf_field() !!}
                <div class="row mb-3">
                  <label for="squad-name" class="col-sm-1 col-form-label">Name</label>
                  <div class="col-sm-11">
                    <input type="text" name="name" maxlength="255" id="squad-name" class="form-control" />
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="squad-type" class="col-sm-1 col-form-label">Type</label>
                  <div class="col-sm-11">
                    <select name="type" id="squad-type" class="form-control">
                      <option value="manual">Manual</option>
                      <option value="auto" selected="selected">Auto</option>
                      <option value="hidden">Hidden</option>
                    </select>
                  </div>
                </div>
                <div class="row offset-sm-1 mb-3">
                  <label for="squad-classified" class="form-check mb-2">
                      <input type="checkbox" name="classified" id="squad-classified" class="form-check-input" />
                      <span class="form-check-label">Classified?</span>
                      <span class="form-check-description">If a squad is classified, then only moderators and administrators will be able to see the member list.</span>
                  </label>
                </div>
                <input type="hidden" name="description" />
                <input type="hidden" name="filters" />
                <input type="file" name="logo" accept="image/png, image/jpeg" id="file-image" class="d-none" />
                <div id="squad-description"></div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="float-end">
            <button type="submit" form="squad-form" class="btn btn-success d-sm-inline-block">
              <i class="fas fa-check-circle"></i> Submit
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('web::components.filters.modals.filters.filters', [
    'filters' => [
        (object) ['name' => 'scopes', 'src' => route('seatcore::fastlookup.scopes'), 'path' => 'refresh_tokens', 'field' => 'scopes', 'label' => 'Scopes'],
        (object) ['name' => 'character', 'src' => route('seatcore::fastlookup.characters'), 'path' => 'characters', 'field' => 'character_infos.character_id', 'label' => 'Character'],
        (object) ['name' => 'title', 'src' => route('seatcore::fastlookup.titles'), 'path' => 'characters.titles', 'field' => 'id', 'label' => 'Title'],
        (object) ['name' => 'corporation', 'src' => route('seatcore::fastlookup.corporations'), 'path' => 'characters.affiliation', 'field' => 'corporation_id', 'label' => 'Corporation'],
        (object) ['name' => 'alliance', 'src' => route('seatcore::fastlookup.alliances'), 'path' => 'characters.affiliation', 'field' => 'alliance_id', 'label' => 'Alliance'],
        (object) ['name' => 'skill', 'src' => route('seatcore::fastlookup.skills'), 'path' => 'characters.skills', 'field' => 'skill_id', 'label' => 'Skill'],
        (object) ['name' => 'skill_level', 'src' => [['id' => 1, 'text' => 'Level 1'], ['id' => 2, 'text' => 'Level 2'], ['id' => 3, 'text' => 'Level 3'], ['id' => 4, 'text' => 'Level 4'], ['id' => 5, 'text' => 'Level 5']], 'path' => 'characters.skills', 'field' => 'trained_skill_level', 'label' => 'Skill Level'],
        (object) ['name' => 'type', 'src' => route('seatcore::fastlookup.items'), 'path' => 'characters.assets', 'field' => 'type_id', 'label' => 'Item'],
        (object) ['name' => 'role', 'src' => route('seatcore::fastlookup.roles'), 'path' => 'characters.corporation_roles', 'field' => 'role', 'label' => 'Role'],
    ],
  ])
@endsection

@push('head')
  <link href="{{ asset('web/css/quill.snow.css') }}" rel="stylesheet" />
@endpush

@push('javascript')
  <script src="{{ asset('web/js/quill.min.js') }}"></script>

  <script>
    Quill.prototype.getHtml = function () {
        let html = this.container.querySelector('.ql-editor').innerHTML;
        html = html.replace(/<p>(<br>|<br\/>|<br\s\/>|\s+|)<\/p>/gmi, "");
        return html;
    };

    let editor = new Quill('#squad-description', {
        modules: {
            toolbar: [
                [{'header': ['1', '2', '3', '4', '5', '6', false]}, {'color': []}],
                ['bold', 'italic', 'underline', 'strike'],
                [{'list': 'ordered'}, {'list': 'bullet'}],
                [{'align': []}, {'indent': '-1'}, {'indent': '+1'}],
                ['link'],
                ['clean']
            ]
        },
        placeholder: 'Compose squad description...',
        theme: 'snow'
    });

    let squadLogo = document.getElementById('squad-logo');
    squadLogo.addEventListener('mouseenter', function (e) {
        e.target.classList.add('border-info');
    });
    squadLogo.addEventListener('mouseleave', function (e) {
        e.target.classList.remove('border-info');
    });
    squadLogo.addEventListener('click', function (e) {
        document.getElementById('file-image').click();
    });

    let fileImage = document.getElementById('file-image');
    fileImage.addEventListener('change', function (e) {
        readImage(this, '#squad-logo');
    });

    let squadForm = document.getElementById('squad-form');
    squadForm.addEventListener('submit', function (e) {
        let descriptionField = document.querySelector('input[name="description"]');
        let filtersField = document.querySelector('input[name="filters"]');

        descriptionField.value = editor.getHtml();
        if (descriptionField.value === '<p><br></p>')
            descriptionField.value = '';
        filtersField.value = document.getElementById('filters-btn').dataset.filters;
    });

    function readImage(input, id) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                document.querySelector(id).setAttribute('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
  </script>
@endpush
