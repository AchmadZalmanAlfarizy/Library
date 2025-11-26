<template>
    <div class="container mt-5">
      <h2>Detail Buku</h2>
  
      <div class="card">
        <!-- Book Cover Card -->
        <div class="row no-gutters">
          <div class="col-md-4">
            <div class="card-img-container p-3">
              <img 
                :src="book.book_cover || placeholderImage" 
                :alt="book.title || 'Book Cover'"
                class="card-img book-cover-image"
                @error="handleImageError"
              />
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
              <router-link :to="{ name: 'BookList' }" class="btn btn-secondary">Kembali</router-link>
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
        placeholderImage: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjM2MCIgdmlld0JveD0iMCAwIDMwMCAzNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMzYwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMjAgMTIwSDEwNVYxMDVIMTIwVjEyMFpNMTk1IDEyMEgxODBWMTA1SDE5NVYxMjBaTTE5NSAxOTVIMTgwVjE4MEgxOTVWMTk1Wk0xMjAgMTk1SDEwNVYxODBIMTIwVjE5NVoiIGZpbGw9IiM5Q0E0QUYiLz4KPHA+Cjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjNjM3MzgxIiBmb250LXNpemU9IjE2IiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiPk5vIEltYWdlPC90ZXh0Pgo8L3N2Zz4K'
      };
    },
    async created() {
      await this.fetchBook();
    },
    methods: {
      async fetchBook() {
        try {
          const response = await api.get(`/books/${this.$route.params.id}`);
          this.book = response.data;
        } catch (error) {
          console.error('Error fetching book:', error);
          this.$router.push({ name: 'BookList' });
        }
      },
      handleImageError(event) {
        event.target.src = this.placeholderImage;
      }
    },
  };
  </script>

  <style scoped>
  .card-img-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 300px;
  }

  .book-cover-image {
    max-width: 100%;
    max-height: 400px;
    width: auto;
    height: auto;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
  }

  .book-cover-image:hover {
    transform: scale(1.05);
  }

  .card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 12px;
    overflow: hidden;
  }

  .card-body {
    padding: 2rem;
  }

  .card-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
  }

  .card-text {
    font-size: 1rem;
    line-height: 1.6;
    color: #4a5568;
    margin-bottom: 1.5rem;
  }

  .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    padding: 0.5rem 1.5rem;
    border-radius: 6px;
    transition: all 0.3s ease;
  }

  .btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
    transform: translateY(-1px);
  }

  @media (max-width: 768px) {
    .card-img-container {
      min-height: 200px;
    }
    
    .book-cover-image {
      max-height: 250px;
    }
    
    .card-body {
      padding: 1.5rem;
    }
  }
  </style>