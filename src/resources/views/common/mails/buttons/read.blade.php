<button type="button" data-toggle="modal" data-target="#mail-content" class="btn btn-sm btn-primary"
        data-url="{{ route('character.view.mail.read', ['character_id' => $character_id, 'message_id' => $mail_id]) }}">
  <i class="fa fa-envelope"></i> {{ trans('web::mail.read') }}
</button>