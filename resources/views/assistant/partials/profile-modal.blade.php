@php($user = auth()->user())

<div id="app">
    <profile-modal
        :user="{{ json_encode($user) }}"
        update-url="{{ route('assistant.profile.update') }}"
        role="assistant"
    ></profile-modal>
</div>
<div id="app">
    <announcement-modal
        :user="{{ json_encode($user) }}"
        update-url="{{ route('assistant.profile.update') }}"
        role="assistant"
    ></announcement-modal>
</div>



