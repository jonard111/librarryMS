<template>
    <div class="modal fade" :id="modalId" tabindex="-1" :aria-labelledby="modalId + 'Label'" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="modalId + 'Label'">
                        <i class="fas fa-chart-line me-2"></i> Generate Report
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ errorMessage }}
                            <button type="button" class="btn-close" @click="errorMessage = ''" aria-label="Close"></button>
                        </div>

                        <div class="mb-3">
                            <label for="reportType" class="form-label">Report Type <span class="text-danger">*</span></label>
                            <select 
                                v-model="formData.report_type" 
                                class="form-select"
                                :class="{ 'is-invalid': errors.report_type }"
                                id="reportType" 
                                required
                            >
                                <option value="">Select Report Type</option>
                                <option value="borrow">Borrow Transactions</option>
                                <option value="return">Return Reports</option>
                                <option value="penalty">Penalty Summary</option>
                                <option value="user">User Activity</option>
                            </select>
                            <div v-if="errors.report_type" class="invalid-feedback">
                                {{ errors.report_type[0] }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input 
                                    type="date" 
                                    v-model="formData.start_date" 
                                    class="form-control" 
                                    id="startDate"
                                />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input 
                                    type="date" 
                                    v-model="formData.end_date" 
                                    class="form-control" 
                                    id="endDate"
                                />
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>Leave dates empty to generate report for all time.</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sidebar" :disabled="loading || !formData.report_type">
                            <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status"></span>
                            <i v-else class="fas fa-download me-2"></i>
                            {{ loading ? 'Generating...' : 'Generate Report' }}
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
    name: 'ReportModal',
    props: {
        generateUrl: {
            type: String,
            required: true
        },
        modalId: {
            type: String,
            default: 'generateReportModal'
        }
    },
    data() {
        return {
            formData: {
                report_type: '',
                start_date: '',
                end_date: ''
            },
            errors: {},
            loading: false,
            errorMessage: ''
        };
    },
    methods: {
        async handleSubmit() {
            this.loading = true;
            this.errors = {};
            this.errorMessage = '';

            try {
                const response = await axios.post(this.generateUrl, this.formData);
                
                if (response.data.success || response.status === 200) {
                    // Emit event with report data
                    this.$emit('report-generated', response.data);
                    
                    // Close modal
                    const modalElement = document.getElementById(this.modalId);
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        }
                    }
                    
                    // Reset form
                    this.resetForm();
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                    this.errorMessage = 'Please correct the errors below.';
                } else {
                    this.errorMessage = error.response?.data?.message || 'Failed to generate report. Please try again.';
                }
            } finally {
                this.loading = false;
            }
        },
        resetForm() {
            this.formData = {
                report_type: '',
                start_date: '',
                end_date: ''
            };
            this.errors = {};
        }
    }
};
</script>

