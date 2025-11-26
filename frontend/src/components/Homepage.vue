<template>
    <div>
      <div v-if="isAdminAuthenticated">
        <p>Mengarahkan ke Dashboard Admin...</p>
      </div>
      <div v-else-if="isMemberAuthenticated">
        <p>Mengarahkan ke Daftar Buku Member...</p>
      </div>
      <div v-else class="hero-section">
        <div class="hero-content">
          <h1 class="hero-title">Selamat Datang di Perpustakaan Digital</h1>
          <p class="hero-subtitle">Temukan dunia pengetahuan dengan koleksi buku terbaik kami. Silakan login untuk melanjutkan perjalanan literasi Anda.</p>
          <div class="hero-buttons">
            <router-link :to="{ name: 'Login' }" class="btn btn-primary me-3">Login Admin</router-link>
            <router-link :to="{ name: 'MemberLogin' }" class="btn btn-success">Login Member</router-link>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        isAdminAuthenticated: false,
        isMemberAuthenticated: false,
      };
    },
    created() {
      this.isAdminAuthenticated = !!localStorage.getItem('token');
      this.isMemberAuthenticated = !!localStorage.getItem('memberToken');
  
      if (this.isAdminAuthenticated) {
        this.$router.push({ name: 'Dashboard' });
      } else if (this.isMemberAuthenticated) {
        this.$router.push({ name: 'BookListMember' });
      }
    },
  };
  </script>