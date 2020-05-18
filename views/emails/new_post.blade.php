@component('mail::message')
## New post from {{ $post->author->first_name  }} posted

{!! $post->content !!}<br><br><br>

<hr>
<small>You are receiving this because you are subscribed to this thread. You can <a href="">unsubscribe</a> to this thread if you don't like receiving updates about the topic.</small>
@endcomponent
