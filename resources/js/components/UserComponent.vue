<template>
    <div>
        <h2>Quản lý người dùng</h2>
        <form @submit.prevent="addUser">
            <input v-model="newUser.name" placeholder="Tên người dùng" required>
            <button type="submit">Thêm người dùng</button>
        </form>
        <ul>
            <li v-for="user in users" :key="user.id">{{ user.name }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    data() {
        return {
            newUser: { name: '' },
            users: []
        }
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        async getUsers() {
            try {
                const response = await axios.get('/api/users');
                this.users = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        async addUser() {
            try {
                const response = await axios.post('/api/users', this.newUser);
                this.users.push(response.data);
                this.newUser.name = '';
            } catch (error) {
                console.error(error);
            }
        }
    }
}
</script>

<style scoped>
h2 {
    color: #42b983;
}
</style>
