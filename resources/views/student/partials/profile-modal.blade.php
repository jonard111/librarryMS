@php($user = auth()->user())

<div id="app">
    <profile-modal
        :user="{{ json_encode($user) }}"
        update-url="{{ route('student.profile.update') }}"
        role="student"
    ></profile-modal>
</div>
