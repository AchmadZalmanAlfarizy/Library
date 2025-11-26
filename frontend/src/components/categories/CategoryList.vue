<template>
    <div class="container mt-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Kategori</h2>
        <router-link :to="{ name: 'CategoryCreate' }" class="btn btn-primary"
          >Tambah Kategori</router-link
        >
      </div>
  
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="category in categories" :key="category.id">
            <td>{{ category.id }}</td>
            <td>{{ category.name }}</td>
            <td>
              <router-link
                :to="{ name: 'CategoryEdit', params: { id: category.id } }"
                class="btn btn-warning btn-sm"
                >Edit</router-link
              >
              <button
                @click="deleteCategory(category.id)"
                class="btn btn-danger btn-sm"
              >
                Hapus
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import api from "../../services/api";
  
  export default {
    data() {
      return {
        categories: [],
      };
    },
    created() {
      this.fetchCategories();
    },
    methods: {
      async fetchCategories() {
        try {
          const response = await api.get("/categories");
          this.categories = response.data;
        } catch (error) {
          console.error("Error fetching categories:", error);
        }
      },
      async deleteCategory(id) {
        if (confirm("Apakah Anda yakin ingin menghapus kategori ini?")) {
          try {
            await api.delete(`/categories/${id}`);
            this.fetchCategories(); // Refresh the category list
          } catch (error) {
            console.error("Error deleting category:", error);
            alert(error.response.data.message);
          }
        }
      },
    },
  };
  </script>