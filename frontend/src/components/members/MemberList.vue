<template>
    <div class="container mt-5">
      <h2>Daftar Anggota</h2>
  
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>ID Anggota</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th>Alamat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="member in members" :key="member.id">
            <td>{{ member.id }}</td>
            <td>{{ member.name }}</td>
            <td>{{ member.member_id }}</td>
            <td>{{ member.email }}</td>
            <td>{{ member.phone_number }}</td>
            <td>{{ member.address }}</td>
            <td>
              <button @click="deleteMember(member.id)" class="btn btn-danger btn-sm">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import api from '../../services/api';
  
  export default {
    data() {
      return {
        members: [],
      };
    },
    created() {
      this.fetchMembers();
    },
    methods: {
      async fetchMembers() {
        try {
          const response = await api.get('/members');
          this.members = response.data;
        } catch (error) {
          console.error('Error fetching members:', error);
        }
      },
      async deleteMember(id) {
        if (confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
          try {
            await api.delete(`/members/${id}`);
            this.fetchMembers(); // Refresh the member list
          } catch (error) {
            console.error('Error deleting member:', error);
          }
        }
      },
    },
  };
  </script>