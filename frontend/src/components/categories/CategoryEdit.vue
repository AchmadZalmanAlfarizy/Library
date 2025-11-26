<template>
    <div class="container mt-5">
      <h2>Edit Kategori</h2>
  
      <form @submit.prevent="updateCategory">
        <div class="mb-3">
          <label for="name" class="form-label">Nama Kategori</label>
          <input
            type="text"
            class="form-control"
            id="name"
            v-model="category.name"
            required
          />
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <router-link :to="{ name: 'CategoryList' }" class="btn btn-secondary ms-2"
          >Kembali</router-link
        >
      </form>
    </div>
  </template>
  
  <script>
  import api from "../../services/api";
  
  export default {
    data() {
      return {
        category: {
          name: "",
        },
      };
    },
    async created() {
      await this.fetchCategory();
    },
    methods: {
      async fetchCategory() {
        try {
          const response = await api.get(`/categories/${this.$route.params.id}`);
          this.category = response.data;
        } catch (error) {
          console.error("Error fetching category:", error);
          this.$router.push({ name: "CategoryList" });
        }
      },
      async updateCategory() {
        try {
          await api.put(`/categories/${this.$route.params.id}`, this.category);
          this.$router.push({ name: "CategoryList" });
        } catch (error) {
          console.error("Error updating category:", error);
          alert(error.response.data.message);
        }
      },
    },
  };
  </script>