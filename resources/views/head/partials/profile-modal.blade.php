@php($user = auth()->user())

<div id="app">
    <profile-modal
        :user="{{ json_encode($user) }}"
        update-url="{{ route('head.profile.update') }}"
        role="headlibrarian"
    ></profile-modal>
</div>



