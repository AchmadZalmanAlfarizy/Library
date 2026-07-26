<template>
  <div class="container mt-5">
    <h2 class="fw-bold mb-4 text-dark">Dashboard</h2>

    <div class="row">
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Buku Terpopuler</h5>
            <canvas ref="popularBooksChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Peminjaman Bulan Ini</h5>
            <canvas ref="loansPerMonthChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title mb-3">Anggota Aktif</h5>
            <ul class="list-group list-group-flush">
              <li v-for="member in activeMembers" :key="member.id" class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ member.name }}</span>
                <span class="badge bg-primary rounded-pill">{{ member.loans_count }} peminjaman</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../services/api';
import Chart from 'chart.js/auto';

export default {
  data() {
    return {
      popularBooks: [],
      loansPerMonth: [],
      activeMembers: [],
      popularBooksChart: null,
      loansPerMonthChart: null,
    };
  },
  async created() {
    await this.fetchDashboardData();
    this.createPopularBooksChart();
    this.createLoansPerMonthChart();
  },
  methods: {
    async fetchDashboardData() {
      try {
        const response = await api.get('/dashboard');
        this.popularBooks = response.data.popular_books;
        this.loansPerMonth = response.data.loans_per_month;
        this.activeMembers = response.data.active_members;
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
      }
    },
    createPopularBooksChart() {
      const labels = this.popularBooks.map(book => book.title);
      const data = this.popularBooks.map(book => book.loans_count);

      this.popularBooksChart = new Chart(this.$refs.popularBooksChart, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Jumlah Peminjaman',
            data: data,
            backgroundColor: 'rgba(79, 70, 229, 0.85)',
            borderColor: '#4f46e5',
            borderWidth: 1.5,
            borderRadius: 6,
            borderSkipped: false,
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              labels: {
                color: '#475569',
                font: { family: "'Inter', sans-serif", weight: '500' }
              }
            }
          },
          scales: {
            x: {
              ticks: { color: '#64748b' },
              grid: { color: 'rgba(226, 232, 240, 0.6)' }
            },
            y: {
              beginAtZero: true,
              ticks: { color: '#64748b', precision: 0 },
              grid: { color: 'rgba(226, 232, 240, 0.6)' }
            }
          }
        }
      });
    },
    createLoansPerMonthChart() {
      const labels = this.loansPerMonth.map(loan => this.getMonthName(loan.month));
      const data = this.loansPerMonth.map(loan => loan.total);

      this.loansPerMonthChart = new Chart(this.$refs.loansPerMonthChart, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Jumlah Peminjaman',
            data: data,
            fill: true,
            backgroundColor: 'rgba(14, 165, 233, 0.12)',
            borderColor: '#0ea5e9',
            borderWidth: 3,
            tension: 0.35,
            pointBackgroundColor: '#0ea5e9',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              labels: {
                color: '#475569',
                font: { family: "'Inter', sans-serif", weight: '500' }
              }
            }
          },
          scales: {
            x: {
              ticks: { color: '#64748b' },
              grid: { color: 'rgba(226, 232, 240, 0.6)' }
            },
            y: {
              beginAtZero: true,
              ticks: { color: '#64748b', precision: 0 },
              grid: { color: 'rgba(226, 232, 240, 0.6)' }
            }
          }
        }
      });
    },
    getMonthName(monthNumber) {
      const date = new Date();
      date.setMonth(monthNumber - 1);
      return date.toLocaleString('default', { month: 'long' });
    }
  },
};
</script>

<style scoped>
/* Styling Kartu Dashboard Modern */
.card {
  border: none;
  border-radius: 12px;
  background-color: #ffffff;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.card-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 1.25rem;
}

/* Styling List Anggota Aktif */
.list-group-item {
  border-color: #f1f5f9;
  padding: 0.75rem 0.25rem;
  color: #334155;
  font-weight: 500;
  font-size: 0.95rem;
}

.list-group-item:last-child {
  border-bottom: none;
}

.badge {
  font-weight: 500;
  padding: 0.4em 0.75em;
  font-size: 0.8rem;
}
</style>