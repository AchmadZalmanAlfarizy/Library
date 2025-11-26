<template>
    <div class="container mt-5">
      <h2>Daftar Peminjaman</h2>
  
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
            <th>Aksi</th>
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
            <td>
              <button v-if="!loan.return_date" @click="returnBook(loan.id)" class="btn btn-success btn-sm">
                Kembalikan
              </button>
              <button v-else disabled class="btn btn-success btn-sm">
                Sudah Kembali
              </button>
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
        loans: [],
      };
    },
    created() {
      this.fetchLoans();
    },
    methods: {
      async fetchLoans() {
        try {
          const response = await api.get('/loans');
          this.loans = response.data;
        } catch (error) {
          console.error('Error fetching loans:', error);
        }
      },
      async returnBook(loanId) {
        try {
          const today = new Date().toISOString().slice(0, 10);
          const response = await api.put(`/loans/${loanId}/return`, { return_date: today });
          
          // Find the loan in the loans array and update its return_date and fine_amount
          const updatedLoanIndex = this.loans.findIndex(loan => loan.id === loanId);
          if (updatedLoanIndex !== -1) {
            this.loans[updatedLoanIndex].return_date = today;
            this.loans[updatedLoanIndex].fine_amount = response.data.fine;
          }
  
        } catch (error) {
          console.error('Error returning book:', error);
        }
      },
    },
  };
  </script>