@php($user = auth()->user())

<div id="app">
    <profile-modal
        :user="{{ json_encode($user) }}"
        update-url="{{ route('faculty.profile.update') }}"
        role="faculty"
    ></profile-modal>
</div>






