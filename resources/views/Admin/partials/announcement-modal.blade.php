<div id="app">
    <announcement-modal
        store-url="{{ route('admin.announcement.store') }}"
        :audience-options='@json($audienceOptions)'
        modal-id="createAnnouncementModal"
    ></announcement-modal>
</div>
