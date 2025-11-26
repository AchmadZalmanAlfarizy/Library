<template>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <router-link class="navbar-brand" to="/">
        <i class="bi bi-book-half me-2"></i>
        Perpustakaan Digital
      </router-link>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Menu Admin -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" v-if="isAdminAuthenticated">
          <li class="nav-item">
            <router-link class="nav-link" active-class="active" to="/dashboard"
              >Dashboard</router-link
            >
          </li>
          <li class="nav-item">
            <router-link class="nav-link" active-class="active" to="/books"
              >Buku</router-link
            >
          </li>
          <li class="nav-item">
            <router-link class="nav-link" active-class="active" to="/categories"
              >Kategori</router-link
            >
          </li>
          <li class="nav-item">
            <router-link class="nav-link" active-class="active" to="/members"
              >Anggota</router-link
            >
          </li>
          <li class="nav-item">
            <router-link class="nav-link" active-class="active" to="/loans"
              >Peminjaman</router-link
            >
          </li>
          <li class="nav-item">
            <router-link
              class="nav-link"
              active-class="active"
              to="/reports/loans"
              >Laporan Peminjaman</router-link
            >
          </li>
        </ul>
        <!-- Menu Member -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" v-if="isMemberAuthenticated">
          <li class="nav-item">
            <router-link class="nav-link" active-class="active" to="/member/books"
              >Buku</router-link
            >
          </li>
          <li class="nav-item">
            <router-link class="nav-link" active-class="active" to="/member/history"
              >Riwayat Peminjaman</router-link
            >
          </li>
          <li class="nav-item">
            <router-link class="nav-link" active-class="active" to="/member/profile"
              >Profil</router-link
            >
          </li>
        </ul>

        <!-- Menu untuk yang belum login -->
        <ul class="navbar-nav ms-auto">
          <li class="nav-item" v-if="!isAdminAuthenticated && !isMemberAuthenticated">
            <router-link class="nav-link" active-class="active" to="/login"
              >Login Admin</router-link
            >
          </li>
          <li class="nav-item" v-if="!isAdminAuthenticated && !isMemberAuthenticated">
            <router-link class="nav-link" active-class="active" to="/member/login"
              >Login Member</router-link
            >
          </li>
          <li class="nav-item" v-if="!isAdminAuthenticated && !isMemberAuthenticated">
            <router-link class="nav-link" active-class="active" to="/member/register"
              >Register Member</router-link
            >
          </li>

          <!-- Logout Admin -->
          <li class="nav-item" v-if="isAdminAuthenticated">
            <a class="nav-link" href="#" @click.prevent="logoutAdmin">
              <i class="bi bi-box-arrow-right me-1"></i>
              Logout Admin
            </a>
          </li>
          <!-- Logout Member -->
          <li class="nav-item" v-if="isMemberAuthenticated">
            <a class="nav-link" href="#" @click.prevent="logoutMember">
              <i class="bi bi-box-arrow-right me-1"></i>
              Logout Member
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  computed: {
    ...mapGetters(['isAdminAuthenticated', 'isMemberAuthenticated']),
  },
  methods: {
    ...mapActions(['logoutAdmin', 'logoutMember']),
  },
};
</script>

<style scoped>
.navbar-brand {
  font-weight: 600;
}

.router-link-exact-active {
  font-weight: 500;
}
</style>