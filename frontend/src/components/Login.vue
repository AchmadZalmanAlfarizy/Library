<template>
  <div class="login-container">
    <div class="container">
      <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4">
          <div class="card login-card border-0 shadow-lg">
            <div class="card-header text-center bg-transparent border-0 pt-4 pb-0">
              <div class="icon-circle mb-3 mx-auto">
                <i class="bi bi-shield-lock-fill fs-2 text-warning"></i>
              </div>
              <h4 class="fw-bold text-dark mb-1">Login Admin</h4>
              <p class="text-muted small">Silakan masuk ke panel administrator</p>
            </div>
            <div class="card-body p-4">
              <form @submit.prevent="handleLogin">
                <div class="mb-3">
                  <label for="email" class="form-label fw-semibold text-secondary small">Email</label>
                  <input 
                    type="email" 
                    class="form-control form-control-lg" 
                    id="email" 
                    v-model="email" 
                    required 
                    placeholder="Masukkan email Anda"
                  >
                </div>
                <div class="mb-4">
                  <label for="password" class="form-label fw-semibold text-secondary small">Password</label>
                  <input 
                    type="password" 
                    class="form-control form-control-lg" 
                    id="password" 
                    v-model="password" 
                    required 
                    placeholder="Masukkan password Anda"
                  >
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">
                  Login
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
  data() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    ...mapActions(['loginAdmin']),
    async handleLogin() {
      try {
        const success = await this.loginAdmin({
          email: this.email,
          password: this.password,
        });
        if (success) {
          this.$router.push('/dashboard');
        }
      } catch (error) {
        console.error('Login failed:', error);
        alert(error.response?.data?.message || 'Login gagal. Periksa kembali kredensial Anda.');
      }
    },
  },
};
</script>

<style scoped>
/* Gradient Background untuk seluruh halaman */
.login-container {
  background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 50%, #06b6d4 100%);
  min-height: 100vh;
}

/* Tampilan Card Modern dengan Glassmorphism halus */
.login-card {
  border-radius: 1rem;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

/* Lingkaran Ikon Header */
.icon-circle {
  width: 60px;
  height: 60px;
  background-color: #fff8e6;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Styling Input Form */
.form-control {
  border-radius: 0.5rem;
  border: 1px solid #e2e8f0;
  font-size: 0.95rem;
  transition: all 0.2s ease-in-out;
}

.form-control:focus {
  border-color: #4f46e5;
  box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
}

/* Styling Tombol Login */
.btn-primary {
  background: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);
  border: none;
  border-radius: 0.5rem;
  font-size: 1rem;
  padding: 0.75rem;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: linear-gradient(135deg, #4338ca 0%, #1d4ed8 100%);
  transform: translateY(-1px);
  box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3) !important;
}

.btn-primary:active {
  transform: translateY(0);
}
</style>