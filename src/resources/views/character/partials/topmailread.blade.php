<a href="#" class="btn btn-xs btn-primary mail-content" data-toggle="modal" data-bm-close="#topMailContentModal" data-bm-open="#mailContentModal" data-url="{{ route('character.view.mail.read', ['characterID' => $row->character_id, 'message_id' => $row->mail_id]) }}">
  <i class="fa fa-envelope"></i>
  {{ trans('web::seat.read') }}
</a>
