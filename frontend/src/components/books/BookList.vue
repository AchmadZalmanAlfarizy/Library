<template>
    <div class="container mt-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
          <h2>Daftar Buku</h2>
          <router-link :to="{ name: 'BookCreate' }" class="btn btn-primary">Tambah Buku</router-link>
      </div>
  
      <div class="mb-4">
          <input type="text" v-model="searchQuery" @input="searchBooks" placeholder="Cari buku..." class="form-control">
      </div>
  
      <!-- Books Grid -->
      <div class="row" v-if="books.length > 0">
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" v-for="book in books" :key="book.id">
          <div class="card book-card h-100">
            <!-- Book Cover -->
            <div class="book-cover-container">
              <img 
                :src="book.book_cover || placeholderImage" 
                :alt="book.title"
                class="card-img-top book-cover-img"
                @error="handleImageError"
              />
            </div>
            
            <!-- Card Body -->
            <div class="card-body d-flex flex-column">
              <h5 class="card-title text-truncate" :title="book.title">{{ book.title }}</h5>
              <p class="card-text">
                <small class="text-muted">Penulis:</small><br>
                <span class="text-truncate d-block" :title="book.author">{{ book.author }}</span>
              </p>
              <p class="card-text">
                <small class="text-muted">Kategori:</small><br>
                <span class="badge bg-secondary">{{ book.category.name }}</span>
              </p>
              <p class="card-text">
                <small class="text-muted">Stok</small>
                <span class="badge" :class="book.stock > 0 ? 'bg-success' : 'bg-danger'">
                  {{ book.stock }}
                </span>
              </p>
              
              <!-- Action Buttons -->
              <div class="mt-auto">
                <div class="btn-group w-100" role="group">
                  <router-link :to="{ name: 'BookDetails', params: { id: book.id } }" 
                               class="btn btn-info btn-sm">Detail</router-link>
                  <router-link :to="{ name: 'BookEdit', params: { id: book.id } }" 
                               class="btn btn-warning btn-sm">Edit</router-link>
                  <button @click="deleteBook(book.id)" class="btn btn-danger btn-sm">Hapus</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- No Books Found -->
      <div v-else class="text-center py-5">
        <h4 class="text-muted">Tidak ada buku ditemukan</h4>
        <p class="text-muted">{{ searchQuery ? 'Coba ubah kata kunci pencarian' : 'Mulai dengan menambahkan buku baru' }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import api from '../../services/api';
  
  export default {
    data() {
      return {
        books: [],
        searchQuery: '',
        searchTimeout: null,
        placeholderImage: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjM2MCIgdmlld0JveD0iMCAwIDMwMCAzNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMzYwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMjAgMTIwSDEwNVYxMDVIMTIwVjEyMFpNMTk1IDEyMEgxODBWMTA1SDE5NVYxMjBaTTE5NSAxOTVIMTgwVjE4MEgxOTVWMTk1Wk0xMjAgMTk1SDEwNVYxODBIMTIwVjE5NVoiIGZpbGw9IiM5Q0E0QUYiLz4KPHA+Cjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjNjM3MzgxIiBmb250LXNpemU9IjE2IiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiPk5vIEltYWdlPC90ZXh0Pgo8L3N2Zz4K'
      };
    },
    created() {
      this.fetchBooks();
    },
    methods: {
      async fetchBooks() {
        try {
          const response = await api.get('/books');
          this.books = response.data;
        } catch (error) {
          console.error('Error fetching books:', error);
        }
      },
      async deleteBook(id) {
        if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
          try {
            await api.delete(`/books/${id}`);
            this.fetchBooks();
          } catch (error) {
            console.error('Error deleting book:', error);
          }
        }
      },
      searchBooks() {
        // Clear the existing timeout
        clearTimeout(this.searchTimeout);
        
        // Set a new timeout
        this.searchTimeout = setTimeout(async () => {
          try {
            if (this.searchQuery.trim() === '') {
              // If search query is empty, fetch all books
              return this.fetchBooks();
            }
            
            const response = await api.get('/books/search', {
              params: { query: this.searchQuery }
            });
            this.books = response.data;
          } catch (error) {
            console.error('Error searching books:', error);
          }
        }, 300); // Wait 300ms after user stops typing
      },
      handleImageError(event) {
        event.target.src = this.placeholderImage;
      }
    },
  };
  </script>

  <style scoped>
  .book-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }

  .book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }

  .book-cover-container {
    height: 250px;
    overflow: hidden;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .book-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .book-card:hover .book-cover-img {
    transform: scale(1.05);
  }

  .card-body {
    padding: 1.25rem;
  }

  .card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.75rem;
    line-height: 1.3;
  }

  .card-text {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
  }

  .text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .badge {
    font-size: 0.8rem;
  }

  .btn-group .btn {
    font-size: 0.8rem;
    padding: 0.375rem 0.5rem;
  }

  .btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
  }

  .btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #212529;
  }

  .btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
  }

  @media (max-width: 576px) {
    .col-sm-6 {
      flex: 0 0 100%;
      max-width: 100%;
    }
    
    .book-cover-container {
      height: 200px;
    }
  }
  </style>