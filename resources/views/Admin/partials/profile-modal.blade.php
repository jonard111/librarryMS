@php($user = auth()->user())

<div id="app">
    <profile-modal
        :user="{{ json_encode($user) }}"
        update-url="{{ route('admin.profile.update') }}"
        role="admin"
    ></profile-modal>
</div>



