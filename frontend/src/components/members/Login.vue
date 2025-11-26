<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">Member Login</div>
          <div class="card-body">
            <!-- Form Login -->
            <form @submit.prevent="handleLogin" v-if="!showFindMemberIdForm">
              <div class="mb-3">
                <label for="member_id" class="form-label">Member ID</label>
                <input type="text" class="form-control" id="member_id" v-model="member_id" required>
              </div>
              <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Login</button>
                <router-link to="/member/register" class="btn btn-success">Register</router-link>
              </div>
              <div class="mt-3">
                <a class="text-decoration-none" href="#" @click.prevent="showFindMemberIdForm = true">Lupa Member ID?</a>
              </div>
            </form>

            <!-- Form Pencarian Member ID -->
            <form @submit.prevent="findMemberId" v-if="showFindMemberIdForm">
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" v-model="email" required>
              </div>
              <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-secondary">Cari Member ID</button>
                <button type="button" class="btn btn-light" @click="showFindMemberIdForm = false">Batal</button>
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
import { mapActions } from 'vuex';

export default {
  data() {
    return {
      member_id: '',
      email: '',
      showFindMemberIdForm: false,
    };
  },
  methods: {
    ...mapActions(['loginMember']),
    async handleLogin() {
      try {
        const success = await this.loginMember(this.member_id);
        if (success) {
            this.$router.push('/member/books');
        }
      } catch (error) {
        console.error('Login failed:', error);
        alert(error.message);
      }
    },
    async findMemberId() {
      try {
        const response = await api.get('/members');
        const memberData = response.data.find(member => member.email === this.email);
        if (memberData) {
          alert(`Member ID Anda adalah: ${memberData.member_id}`);
          this.showFindMemberIdForm = false; // Sembunyikan form pencarian
          this.email = ''; // Reset email
        } else {
          alert('Email tidak ditemukan!');
        }
      } catch (error) {
        console.error('Error finding member ID:', error);
        alert('Gagal mencari Member ID. Coba lagi nanti.');
      }
    },
  },
};
</script>