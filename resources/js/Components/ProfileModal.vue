<template>
    <div class="modal fade" :id="modalId" tabindex="-1" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" :class="headerClass">
                    <h5 class="modal-title" :id="modalId + 'Label'">
                        <i class="fas fa-user-circle me-2"></i>My Profile
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ successMessage }}
                            <button type="button" class="btn-close" @click="successMessage = ''" aria-label="Close"></button>
                        </div>
                        <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ errorMessage }}
                            <button type="button" class="btn-close" @click="errorMessage = ''" aria-label="Close"></button>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-5 border-end">
                                <div class="text-center">
                                    <i class="fas fa-user-circle mb-3" :class="iconClass" style="font-size:4.5rem;"></i>
                                    <h4 class="mb-1">{{ user.first_name }} {{ user.last_name }}</h4>
                                    <p class="text-muted">{{ user.email }}</p>
                                </div>
                                <ul class="list-group small">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Role</span>
                                        <span class="badge" :class="roleBadgeClass">{{ user.role }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Status</span>
                                        <span class="badge bg-primary text-uppercase">{{ user.account_status }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Member Since</span>
                                        <span>{{ formatDate(user.registration_date) }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input 
                                        type="text" 
                                        v-model="formData.first_name" 
                                        class="form-control"
                                        :class="{ 'is-invalid': errors.first_name }"
                                        required
                                    >
                                    <div v-if="errors.first_name" class="invalid-feedback">
                                        {{ errors.first_name[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input 
                                        type="text" 
                                        v-model="formData.last_name" 
                                        class="form-control"
                                        :class="{ 'is-invalid': errors.last_name }"
                                        required
                                    >
                                    <div v-if="errors.last_name" class="invalid-feedback">
                                        {{ errors.last_name[0] }}
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input 
                                        type="email" 
                                        v-model="formData.email" 
                                        class="form-control"
                                        :class="{ 'is-invalid': errors.email }"
                                        required
                                    >
                                    <div v-if="errors.email" class="invalid-feedback">
                                        {{ errors.email[0] }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">New Password <span class="text-muted">(optional)</span></label>
                                        <input 
                                            type="password" 
                                            v-model="formData.password" 
                                            class="form-control"
                                            :class="{ 'is-invalid': errors.password }"
                                            autocomplete="new-password"
                                        >
                                        <div v-if="errors.password" class="invalid-feedback">
                                            {{ errors.password[0] }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input 
                                            type="password" 
                                            v-model="formData.password_confirmation" 
                                            class="form-control"
                                            autocomplete="new-password"
                                        >
                                    </div>
                                </div>
                                <small class="text-muted d-block">Leave password fields blank if you don't want to change it.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" :class="submitButtonClass" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                            {{ loading ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ProfileModal',
    props: {
        user: {
            type: Object,
            required: true
        },
        updateUrl: {
            type: String,
            required: true
        },
        role: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            formData: {
                first_name: this.user.first_name || '',
                last_name: this.user.last_name || '',
                email: this.user.email || '',
                password: '',
                password_confirmation: ''
            },
            errors: {},
            loading: false,
            successMessage: '',
            errorMessage: ''
        };
    },
    computed: {
        modalId() {
            return `${this.role}ProfileModal`;
        },
        headerClass() {
            return 'bg-sidebar text-white';
        },
        iconClass() {
            return 'text-sidebar';
        },
        roleBadgeClass() {
            const roleClasses = {
                'admin': 'bg-danger',
                'student': 'bg-success',
                'faculty': 'bg-info',
                'assistant': 'bg-warning',
                'headlibrarian': 'bg-primary'
            };
            return roleClasses[this.role] || 'bg-secondary';
        },
        submitButtonClass() {
            return 'btn-sidebar';
        }
    },
    methods: {
        async handleSubmit() {
            this.loading = true;
            this.errors = {};
            this.errorMessage = '';
            this.successMessage = '';

            try {
                const response = await axios.put(this.updateUrl, this.formData);
                
                if (response.data.success) {
                    this.successMessage = response.data.message || 'Profile updated successfully.';
                    // Update user data
                    this.$emit('profile-updated', response.data.user || this.formData);
                    
                    // Clear password fields
                    this.formData.password = '';
                    this.formData.password_confirmation = '';
                    
                    // Close modal after 1.5 seconds
                    setTimeout(() => {
                        const modalElement = document.getElementById(this.modalId);
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }
                        }
                    }, 1500);
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                    this.errorMessage = 'Please correct the errors below.';
                } else {
                    this.errorMessage = error.response?.data?.message || 'An error occurred while updating your profile.';
                }
            } finally {
                this.loading = false;
            }
        },
        formatDate(date) {
            if (!date) return 'N/A';
            const d = new Date(date);
            return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        }
    },
    watch: {
        user: {
            immediate: true,
            deep: true,
            handler(newUser) {
                if (newUser) {
                    this.formData.first_name = newUser.first_name || '';
                    this.formData.last_name = newUser.last_name || '';
                    this.formData.email = newUser.email || '';
                }
            }
        }
    }
};
</script>

<style scoped>
.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}
</style>

