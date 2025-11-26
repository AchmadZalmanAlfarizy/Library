<template>
    <div class="container mt-5">
      <h2>Tambah Kategori</h2>
  
      <form @submit.prevent="createCategory">
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
    methods: {
      async createCategory() {
        try {
          await api.post("/categories", this.category);
          this.$router.push({ name: "CategoryList" });
        } catch (error) {
          console.error("Error creating category:", error);
          alert(error.response.data.message);
        }
      },
    },
  };
  </script>