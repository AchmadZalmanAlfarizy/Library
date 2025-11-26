// App.vue
<template>
  <div id="app">
    <Navbar />
    <div class="container mt-4">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import Navbar from "./components/Navbar.vue";
import { mapMutations } from 'vuex';

export default {
  components: {
    Navbar,
  },
  methods: {
    ...mapMutations(['SET_ADMIN_AUTH', 'SET_MEMBER_AUTH']),
    checkAuth() {
      // Check admin authentication
      const adminToken = localStorage.getItem('token');
      if (adminToken) {
        this.SET_ADMIN_AUTH(true);
      }
      
      // Check member authentication
      const memberToken = localStorage.getItem('memberToken');
      const memberId = localStorage.getItem('memberId');
      if (memberToken && memberId) {
        this.SET_MEMBER_AUTH(true);
      }
    }
  },
  created() {
    this.checkAuth();
  }
};
</script>