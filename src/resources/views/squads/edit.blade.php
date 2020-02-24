@extends('web::layouts.grids.12')

@section('title', trans_choice('web::squads.squad', 0))
@section('page_header', trans_choice('web::squads.squad', 1))
@section('page_description', 'Update')

@section('full')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <div class="input-group input-group-sm">
              @include('web::components.filters.buttons.filters', ['rules' => $squad->filters])
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="media">
            <img src="{{ $squad->logo }}" width="128" height="128" class="border align-self-center mr-3" id="squad-logo" />
            <div class="media-body">
              <form method="post" action="{{ route('squads.update', $squad->id) }}" enctype="multipart/form-data" id="squad-form">
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}
                <div class="form-group row">
                  <label for="squad-name" class="col-sm-1 col-form-label">Name</label>
                  <div class="col-sm-11">
                    <input type="text" name="name" maxlength="255" value="{{ $squad->name }}" id="squad-name" class="form-control" />
                  </div>
                </div>
                <div class="form-group row">
                  <label for="squad-type" class="col-sm-1 col-form-label">Type</label>
                  <div class="col-sm-11">
                    <select name="type" id="squad-type" class="form-control">
                      @if($squad->type == 'manual')
                        <option value="manual" selected="selected">Manual</option>
                      @else
                        <option value="manual">Manual</option>
                      @endif
                      @if($squad->type == 'auto')
                        <option value="auto" selected="selected">Auto</option>
                      @else
                        <option value="auto">Auto</option>
                      @endif
                      @if($squad->type == 'hidden')
                        <option value="hidden" selected="selected">Hidden</option>
                      @else
                        <option value="hidden">Hidden</option>
                      @endif
                    </select>
                  </div>
                </div>
                <input type="hidden" name="description" value="{{ $squad->getOriginal('filters') }}" />
                <input type="hidden" name="filters" value="{{ $squad->filters }}" />
                <input type="file" name="logo" accept="image/png, image/jpeg" id="file-image" class="d-none" />
                <div id="squad-description"></div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="float-right">
            <button type="submit" form="squad-form" class="btn btn-success">
              <i class="fas fa-"></i> Submit
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('web::components.filters.modals.filters.filters', [
    'filters' => [
        (object) ['name' => 'character', 'src' => route('fastlookup.characters'), 'path' => 'characters', 'field' => 'character_infos.character_id', 'label' => 'Character'],
        (object) ['name' => 'corporation', 'src' => route('fastlookup.corporations'), 'path' => 'characters.affiliation', 'field' => 'corporation_id', 'label' => 'Corporation'],
        (object) ['name' => 'alliance', 'src' => route('fastlookup.alliances'), 'path' => 'characters.affiliation', 'field' => 'alliance_id', 'label' => 'Alliance'],
        (object) ['name' => 'skill', 'src' => route('fastlookup.skills'), 'path' => 'characters.skills', 'field' => 'skill_id', 'label' => 'Skill'],
        (object) ['name' => 'type', 'src' => route('fastlookup.items'), 'path' => 'characters.assets', 'field' => 'type_id', 'label' => 'Item'],
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
        var html = this.container.querySelector('.ql-editor').innerHTML;
        html = html.replace(/<p>(<br>|<br\/>|<br\s\/>|\s+|)<\/p>\r\n/gmi, "");
        return html;
    };

    var editor = new Quill('#squad-description', {
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

    editor.setContents(editor.clipboard.convert('{!! addslashes($squad->description) !!}'), 'silent');

    $('#squad-logo')
        .on('mouseenter', function () {
            $(this).addClass('border-info');
        })
        .on('mouseleave', function () {
            $(this).removeClass('border-info');
        })
        .on('click', function () {
            $('#file-image').click();
        });

    $('#file-image').on('change', function () {
        readImage(this, '#squad-logo');
    });

    $('#squad-form').on('submit', function () {
        $('input[name="description"]').val(editor.getHtml());
        $('input[name="filters"]').val(document.getElementById('filters-btn').dataset.filters);
    });

    function readImage(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(id).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
  </script>
@endpush
