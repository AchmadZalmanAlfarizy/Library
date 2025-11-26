<template>
  <div class="container mt-5">
    <h2>Riwayat Peminjaman</h2>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
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
          <td>{{ loan.book.title }}</td>
          <td>{{ loan.borrow_date }}</td>
          <td>{{ loan.due_date }}</td>
          <td>{{ loan.return_date || '-' }}</td>
          <td>{{ loan.fine_amount }}</td>
          <td>
            <button
              v-if="!loan.return_date"
              @click="returnBook(loan.id)"
              class="btn btn-success btn-sm"
            >
              Kembalikan
            </button>
            <span v-else>Sudah Dikembalikan</span>
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
  async created() {
    await this.fetchLoans();
  },
  methods: {
    async fetchLoans() {
      try {
        const memberId = localStorage.getItem('memberId');
        const response = await api.get(`/loans/history/${memberId}`);
        this.loans = response.data;
      } catch (error) {
        console.error('Error fetching loan history:', error);
      }
    },
    async returnBook(loanId) {
      try {
        const today = new Date().toISOString().slice(0, 10);
        const response = await api.put(`/loans/${loanId}/return`, {
          return_date: today,
        });

        // Update data peminjaman di dalam array 'loans'
        const updatedLoanIndex = this.loans.findIndex(
          (loan) => loan.id === loanId
        );
        if (updatedLoanIndex !== -1) {
          this.loans[updatedLoanIndex].return_date = today;
          this.loans[updatedLoanIndex].fine_amount = response.data.fine;
        }

        alert('Buku berhasil dikembalikan!');
      } catch (error) {
        console.error('Error returning book:', error);
        alert(error.response.data.message);
      }
    },
  },
};
</script>