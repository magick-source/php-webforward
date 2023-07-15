{if (isset($notifications) && $notifications->has_notifications())}
<div class="container mt-5">
  <div class="row">
    <div class="col">
{assign var="notes" value=$notifications->get_notifications()}
{section name="i" loop=$notes}
  <div class="alert {$notes[i]->class()}" role="alert">
  {if isset($notes[i]->title) && $notes[i]->title }
    <h4 class="alert-heading">{$notes[i]->title}</h4>
  {/if}
    <p>{$notes[i]->message}</p>
  </div>
{/section}
    </div>
  </div>
</div>
{$notifications->notifications_shown()}
{/if}
