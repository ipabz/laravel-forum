@component('mail::message')
## New post from {{ $post->author->first_name  }} posted

{!! $post->content !!}<br><br><br>

@if ($subscription)
<hr>
<small>You are receiving this because you are subscribed to this thread. You can <a href="{{ url('/forum/unsubscribe/' . encrypt($subscription->id)) }}">unsubscribe</a> to this thread if you don't like receiving updates about the topic.</small>
@endif
@endcomponent
