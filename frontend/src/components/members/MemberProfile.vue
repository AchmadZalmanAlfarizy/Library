<template>
  <div class="container mt-5">
    <h2>Profil Saya</h2>

    <div class="row">
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <form @submit.prevent="updateProfile">
              <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  v-model="member.name"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  v-model="member.email"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="phone_number" class="form-label">No. Telepon</label>
                <input
                  type="text"
                  class="form-control"
                  id="phone_number"
                  v-model="member.phone_number"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <textarea
                  class="form-control"
                  id="address"
                  v-model="member.address"
                  required
                ></textarea>
              </div>
              <button type="submit" class="btn btn-primary">
                Simpan Perubahan
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../../services/api";

export default {
  data() {
    return {
      member: {
        name: "",
        email: "",
        phone_number: "",
        address: "",
      },
    };
  },
  async created() {
    await this.fetchMemberProfile();
  },
  methods: {
    async fetchMemberProfile() {
      try {
        const memberId = localStorage.getItem("memberId");
        const response = await api.get(`/members/${memberId}`);
        this.member = response.data;
      } catch (error) {
        console.error("Error fetching member profile:", error);
        // Handle error, e.g., redirect to login or show an error message
      }
    },
    async updateProfile() {
      try {
        const memberId = localStorage.getItem("memberId");
        await api.put(`/members/${memberId}`, this.member);
        alert("Profil berhasil diperbarui!");
      } catch (error) {
        console.error("Error updating profile:", error);
        alert(error.response.data.message);
      }
    },
  },
};
</script>