<template>
    <div class="container mt-5">
      <h2>Laporan Peminjaman</h2>
  
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="period" class="form-label">Periode</label>
          <select class="form-select" id="period" v-model="period">
            <option value="daily">Harian</option>
            <option value="weekly">Mingguan</option>
            <option value="monthly">Bulanan</option>
          </select>
        </div>
        <div class="col-md-4" v-if="period !== 'daily'">
          <label for="start_date" class="form-label">Tanggal Mulai</label>
          <input type="date" class="form-control" id="start_date" v-model="startDate">
        </div>
        <div class="col-md-4" v-if="period !== 'daily'">
          <label for="end_date" class="form-label">Tanggal Akhir</label>
          <input type="date" class="form-control" id="end_date" v-model="endDate">
        </div>
        <div class="col-md-4">
          <button @click="fetchLoans" class="btn btn-primary mt-4">Tampilkan</button>
        </div>
      </div>
  
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Anggota</th>
            <th>Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Harus Kembali</th>
            <th>Tanggal Kembali</th>
            <th>Denda</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="loan in loans" :key="loan.id">
            <td>{{ loan.id }}</td>
            <td>{{ loan.member.name }}</td>
            <td>{{ loan.book.title }}</td>
            <td>{{ loan.borrow_date }}</td>
            <td>{{ loan.due_date }}</td>
            <td>{{ loan.return_date || '-' }}</td>
            <td>{{ loan.fine_amount }}</td>
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
        period: 'daily',
        startDate: null,
        endDate: null,
        loans: [],
      };
    },
    created() {
      this.fetchLoans();
    },
    methods: {
      async fetchLoans() {
        try {
          const response = await api.get('/reports/loans', {
            params: {
              period: this.period,
              start_date: this.startDate,
              end_date: this.endDate,
            },
          });
          this.loans = response.data;
        } catch (error) {
          console.error('Error fetching loans:', error);
        }
      },
    },
  };
  </script>