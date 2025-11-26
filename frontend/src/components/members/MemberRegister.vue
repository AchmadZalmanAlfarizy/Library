<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">Member Register</div>
          <div class="card-body">
            <form @submit.prevent="handleRegister">
              <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" v-model="name" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" v-model="email" required>
              </div>
              <div class="mb-3">
                <label for="phone_number" class="form-label">No. Telepon</label>
                <input type="text" class="form-control" id="phone_number" v-model="phone_number" required>
              </div>
              <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea class="form-control" id="address" v-model="address" required></textarea>
              </div>
              <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Register</button>
                <router-link to="/member/login" class="btn btn-success">Login</router-link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../services/api';

export default {
  data() {
    return {
      name: '',
      email: '',
      phone_number: '',
      address: '',
    };
  },
  methods: {
    async handleRegister() {
      try {
        // Panggil API untuk registrasi member
        await api.post('/members/register', {
          name: this.name,
          email: this.email,
          phone_number: this.phone_number,
          address: this.address,
        });

        // Tampilkan pesan sukses
        alert('Registrasi berhasil! Silakan login.');

        // Redirect ke halaman login member
        this.$router.push({ name: 'MemberLogin' });
      } catch (error) {
        console.error('Registration failed:', error);
        alert(error.response?.data?.message || 'Terjadi kesalahan saat registrasi');
      }
    },
  },
};
</script>