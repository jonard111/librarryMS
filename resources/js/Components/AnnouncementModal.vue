<template>
    <div class="modal fade" :id="modalId" tabindex="-1" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="modalId + 'Label'">
                        <i class="fas fa-bullhorn me-2"></i>Create Announcement
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

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input 
                                type="text" 
                                v-model="formData.title" 
                                class="form-control"
                                :class="{ 'is-invalid': errors.title }"
                                placeholder="Enter announcement title" 
                                required
                            />
                            <div v-if="errors.title" class="invalid-feedback">
                                {{ errors.title[0] }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea 
                                v-model="formData.body" 
                                class="form-control"
                                :class="{ 'is-invalid': errors.body }"
                                rows="4" 
                                placeholder="Write your message..." 
                                required
                            ></textarea>
                            <div v-if="errors.body" class="invalid-feedback">
                                {{ errors.body[0] }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Audience</label>
                                <div class="border rounded p-3 bg-light">
                                    <div v-for="option in audienceOptions" :key="option" class="form-check">
                                        <input 
                                            type="checkbox" 
                                            class="form-check-input" 
                                            :value="option"
                                            v-model="formData.audience"
                                            :id="'audience-' + option"
                                        />
                                        <label class="form-check-label" :for="'audience-' + option">
                                            {{ capitalize(option) }}
                                        </label>
                                    </div>
                                    <small class="text-muted">Leave all unchecked to send to everyone.</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select v-model="formData.status" class="form-select">
                                    <option v-for="(label, value) in statusOptions" :key="value" :value="value">
                                        {{ label }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Publish At (optional)</label>
                                <input 
                                    type="datetime-local" 
                                    v-model="formData.publish_at" 
                                    class="form-control"
                                />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expires At (optional)</label>
                                <input 
                                    type="datetime-local" 
                                    v-model="formData.expires_at" 
                                    class="form-control"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sidebar btn-outline-success" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <i v-else class="fas fa-paper-plane me-2"></i>
                            {{ loading ? 'Posting...' : 'Post Announcement' }}
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
    name: 'AnnouncementModal',
    props: {
        storeUrl: {
            type: String,
            required: true
        },
        audienceOptions: {
            type: Array,
            default: () => ['student', 'faculty', 'assistant', 'headlibrarian', 'admin']
        },
        modalId: {
            type: String,
            default: 'createAnnouncementModal'
        }
    },
    data() {
        return {
            formData: {
                title: '',
                body: '',
                audience: [],
                status: 'published',
                publish_at: '',
                expires_at: ''
            },
            statusOptions: {
                'draft': 'Draft',
                'scheduled': 'Scheduled',
                'published': 'Published',
                'archived': 'Archived'
            },
            errors: {},
            loading: false,
            successMessage: '',
            errorMessage: ''
        };
    },
    methods: {
        async handleSubmit() {
            this.loading = true;
            this.errors = {};
            this.errorMessage = '';
            this.successMessage = '';

            try {
                const response = await axios.post(this.storeUrl, this.formData);
                
                if (response.data.success || response.status === 200) {
                    this.successMessage = 'Announcement created successfully!';
                    this.resetForm();
                    
                    // Emit event to parent to refresh announcements
                    this.$emit('announcement-created');
                    
                    // Close modal after 1.5 seconds
                    setTimeout(() => {
                        const modalElement = document.getElementById(this.modalId);
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }
                        }
                        // Reload page to show new announcement
                        window.location.reload();
                    }, 1500);
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                    this.errorMessage = 'Please correct the errors below.';
                } else {
                    this.errorMessage = error.response?.data?.message || 'Failed to create announcement. Please try again.';
                }
            } finally {
                this.loading = false;
            }
        },
        resetForm() {
            this.formData = {
                title: '',
                body: '',
                audience: [],
                status: 'published',
                publish_at: '',
                expires_at: ''
            };
            this.errors = {};
        },
        capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    }
};
</script>

