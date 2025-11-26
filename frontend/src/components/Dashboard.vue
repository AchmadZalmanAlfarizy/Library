<template>
    <div class="container mt-5">
      <h2>Dashboard</h2>
  
      <div class="row">
        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Buku Terpopuler</h5>
              <canvas ref="popularBooksChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Peminjaman Bulan Ini</h5>
              <canvas ref="loansPerMonthChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Anggota Aktif</h5>
              <ul class="list-group">
                <li v-for="member in activeMembers" :key="member.id" class="list-group-item">
                  {{ member.name }} ({{ member.loans_count }} peminjaman)
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
                      backgroundColor: 'rgba(255, 215, 0, 0.6)',
                      borderColor: '#ffd700',
                      borderWidth: 2,
                      borderRadius: 5,
                      borderSkipped: false,
                  }]
              },
              options: {
                  plugins: {
                      legend: {
                          labels: {
                              color: '#e0e0e0'
                          }
                      }
                  },
                  scales: {
                      x: {
                          ticks: {
                              color: '#e0e0e0'
                          },
                          grid: {
                              color: 'rgba(255, 215, 0, 0.2)'
                          }
                      },
                      y: {
                          beginAtZero: true,
                          ticks: {
                              color: '#e0e0e0'
                          },
                          grid: {
                              color: 'rgba(255, 215, 0, 0.2)'
                          }
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
                      backgroundColor: 'rgba(255, 215, 0, 0.1)',
                      borderColor: '#ffd700',
                      borderWidth: 3,
                      tension: 0.4,
                      pointBackgroundColor: '#ffd700',
                      pointBorderColor: '#fff',
                      pointBorderWidth: 2,
                      pointRadius: 6,
                  }]
              },
              options: {
                  plugins: {
                      legend: {
                          labels: {
                              color: '#e0e0e0'
                          }
                      }
                  },
                  scales: {
                      x: {
                          ticks: {
                              color: '#e0e0e0'
                          },
                          grid: {
                              color: 'rgba(255, 215, 0, 0.2)'
                          }
                      },
                      y: {
                          beginAtZero: true,
                          ticks: {
                              color: '#e0e0e0'
                          },
                          grid: {
                              color: 'rgba(255, 215, 0, 0.2)'
                          }
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