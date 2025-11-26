<template>
    <div class="container mt-5">
      <h2>Daftar Buku</h2>
  
      <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3" v-for="book in books" :key="book.id">
          <div class="card h-100">
            <div class="book-cover-container" v-if="book.book_cover">
              <img :src="book.book_cover" :alt="book.title" class="card-img-top book-cover" />
            </div>
            <div class="book-cover-placeholder" v-else>
              <i class="fas fa-book"></i>
            </div>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">{{ book.title }}</h5>
              <p class="card-text flex-grow-1">
                <strong>Penulis:</strong> {{ book.author }}<br>
                <strong>Penerbit:</strong> {{ book.publisher }}<br>
                <strong>Kategori:</strong> {{ book.category.name }}
              </p>
              <div class="d-flex gap-2 mt-auto">
                <button class="btn btn-primary btn-sm flex-fill" @click="borrowBook(book.id)" :disabled="book.stock <= 0">
                  {{ book.stock > 0 ? 'Pinjam' : 'Habis' }}
                </button>
                <router-link :to="{ name: 'BookDetailsMember', params: { id: book.id } }" class="btn btn-info btn-sm flex-fill">
                  Detail
                </router-link>
              </div>
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
        books: [],
      };
    },
    created() {
      this.fetchBooks();
    },
    methods: {
      async fetchBooks() {
        try {
          const response = await api.get('/books'); // Menggunakan endpoint yang sama dengan admin
          this.books = response.data;
        } catch (error) {
          console.error('Error fetching books:', error);
        }
      },
      async borrowBook(bookId) {
        try {
          const memberId = localStorage.getItem('memberId');
          if (!memberId) {
            alert('Anda harus login sebagai member terlebih dahulu!');
            return;
          }
          
          await api.post(`/members/loans`, {
            member_id: memberId,
            book_id: bookId,
            borrow_date: new Date().toISOString().slice(0, 10),
            due_date: new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().slice(0, 10),
          });

          alert('Buku berhasil dipinjam!');
          this.fetchBooks(); // Refresh the book list to update stock
        } catch (error) {
          console.error('Error borrowing book:', error);
          alert(error.response?.data?.message || 'Terjadi kesalahan saat meminjam buku');
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .book-cover-container {
    height: 250px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f8f9fa;
  }
  
  .book-cover {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }
  
  .book-cover:hover {
    transform: scale(1.05);
  }
  
  .book-cover-placeholder {
    height: 250px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #e9ecef;
    color: #6c757d;
    font-size: 2.5rem;
    border-bottom: 1px solid #dee2e6;
  }
  
  .card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }
  
  .card-body {
    min-height: 180px;
    padding: 1rem;
  }
  
  .card-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
  }
  
  .card-text {
    font-size: 0.875rem;
    line-height: 1.4;
  }
  
  .btn-sm {
    font-size: 0.8rem;
    padding: 0.375rem 0.5rem;
  }
  
  @media (max-width: 576px) {
    .book-cover-container,
    .book-cover-placeholder {
      height: 200px;
    }
    
    .book-cover-placeholder {
      font-size: 2rem;
    }
    
    .card-body {
      min-height: 160px;
      padding: 0.75rem;
    }
  }
  </style>