<a href="#" class="btn btn-sm btn-primary mail-content" data-widget="modal" data-bm-close="#topMailContentModal" data-bm-open="#mailContentModal" data-url="{{ route('seatcore::character.view.mail.read', ['characterID' => $row->character_id, 'message_id' => $row->mail_id]) }}">
  <i class="fa fa-envelope"></i>
  {{ trans('web::seat.read') }}
</a>
