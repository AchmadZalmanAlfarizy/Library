<template>
  <div class="container mt-5">
    <h2>Detail Buku</h2>

    <div class="card">
      <div class="row g-0">
        <div class="col-md-4">
          <div class="book-cover-container" v-if="book.book_cover">
            <img :src="book.book_cover" :alt="book.title" class="book-cover-detail" />
          </div>
          <div class="book-cover-placeholder-detail" v-else>
            <i class="fas fa-book"></i>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title">{{ book.title }}</h5>
            <p class="card-text">
              <strong>Penulis:</strong> {{ book.author }}<br>
              <strong>Penerbit:</strong> {{ book.publisher }}<br>
              <strong>Kategori:</strong> {{ book.category ? book.category.name : '' }}<br>
              <strong>Stok:</strong> {{ book.stock }}
            </p>
            <button class="btn btn-primary" @click="borrowBook(book.id)" :disabled="book.stock <= 0">
                {{ book.stock > 0 ? 'Pinjam' : 'Stok Habis' }}
            </button>
            <router-link :to="{ name: 'BookListMember' }" class="btn btn-secondary ms-2">Kembali</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../services/api';

export default {
  data() {
    return {
      book: {},
    };
  },
  computed: {
    bookId() {
      return this.$route.params.id;
    }
  },
  async created() {
    await this.fetchBook();
  },
  methods: {
    async fetchBook() {
      try {
        const response = await api.get(`/books/${this.bookId}`);
        this.book = response.data;
      } catch (error) {
        console.error('Error fetching book:', error);
        this.$router.push({ name: 'BookListMember' });
      }
    },
    async borrowBook(bookId) {
      try {
        const memberId = localStorage.getItem('memberId');
        await api.post(`/members/loans`, { // Ganti URL endpoint
          member_id: memberId,
          book_id: bookId,
          borrow_date: new Date().toISOString().slice(0, 10),
          due_date: new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().slice(0, 10),
        });

        alert('Buku berhasil dipinjam!');
        this.fetchBook();
      } catch (error) {
        console.error('Error borrowing book:', error);
        alert(error.response.data.message);
      }
    },
  },
};
</script>

<style scoped>
.book-cover-container {
  height: 400px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f8f9fa;
  border-radius: 0.375rem 0 0 0.375rem;
}

.book-cover-detail {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0.375rem 0 0 0.375rem;
}

.book-cover-placeholder-detail {
  height: 400px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #e9ecef;
  color: #6c757d;
  font-size: 4rem;
  border-radius: 0.375rem 0 0 0.375rem;
}

.card {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-body {
  padding: 2rem;
}

@media (max-width: 768px) {
  .book-cover-container,
  .book-cover-placeholder-detail {
    height: 250px;
    border-radius: 0.375rem 0.375rem 0 0;
  }
  
  .book-cover-detail {
    border-radius: 0.375rem 0.375rem 0 0;
  }
}
</style>