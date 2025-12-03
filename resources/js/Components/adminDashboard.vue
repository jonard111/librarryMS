<template>
    <div>
        <h2>Pending Users</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.first_name }} {{ user.last_name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role }}</td>
                    <td>
                        <button @click="approve(user.id)" class="btn btn-success btn-sm">Approve</button>
                        <button @click="reject(user.id)" class="btn btn-danger btn-sm">Reject</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ['pendingUsers'],
    data() {
        return { users: this.pendingUsers }
    },
    methods: {
        approve(id) {
            fetch(`/admin/user/${id}/approve`, { method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}})
                .then(()=> this.users = this.users.filter(u=> u.id !== id))
        },
        reject(id) {
            fetch(`/admin/user/${id}/reject`, { method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}})
                .then(()=> this.users = this.users.filter(u=> u.id !== id))
        }
    }
}
</script>
