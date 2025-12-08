<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  // The Laravel logout URL passed from the Blade template
  logoutUrl: { 
    type: String, 
    required: true,
    default: '/logout' 
  },
});

const modalInstance = ref(null);

// Function to trigger the actual form submission
const performLogout = () => {
  // This mimics submitting the hidden form created in the Blade template
  const form = document.getElementById('logout-form');
  if (form) {
    form.submit();
  }
  // Optionally hide the modal if it's still open
  if (modalInstance.value) {
    modalInstance.value.hide();
  }
};

onMounted(() => {
  // Initialize the Bootstrap modal instance once the component is mounted
  const modalEl = document.getElementById('logoutConfirmModal');
  if (window.bootstrap && modalEl) {
    modalInstance.value = new window.bootstrap.Modal(modalEl);
  }
});
</script>

<template>
  <a href="#" 
     @click.prevent="modalInstance ? modalInstance.show() : performLogout()"
     class="nav-link fw-bold logoutLink">
    <i class="fas fa-sign-out-alt me-2"></i> 
    <span>Logout</span>
  </a>

  <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="logoutConfirmModalLabel">
            <i class="fas fa-exclamation-triangle me-2"></i> Confirm Logout
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to log out of the Library Management System?</p>
          <p class="text-muted small mb-0">You will need to sign in again to access the dashboard.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" @click="performLogout">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
          </button>
        </div>
      </div>
    </div>
  </div>
  
  </template>

<style scoped>
/* Scoped styles are optional but recommended in Vue */
.logoutLink {
  color: #dc3545 !important; /* Red color for visual warning */
}
.logoutLink:hover {
  background-color: #f8d7da; /* Light red background on hover */
}
</style>