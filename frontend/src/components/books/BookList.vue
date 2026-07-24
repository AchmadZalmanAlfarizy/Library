<template>
  <div class="container mt-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Daftar Buku</h2>
      <router-link :to="{ name: 'BookCreate' }" class="btn btn-primary">
        + Tambah Buku
      </router-link>
    </div>

    <!-- Search Input -->
    <div class="mb-4">
      <input 
        type="text" 
        v-model="searchQuery" 
        @input="handleSearch" 
        placeholder="Cari judul atau penulis buku..." 
        class="form-control form-control-lg"
      >
    </div>

    <!-- State Loading -->
    <div v-if="isLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Memuat...</span>
      </div>
      <p class="mt-2 text-muted">Memuat daftar buku...</p>
    </div>

    <!-- State Error -->
    <div v-else-if="errorMessage" class="alert alert-danger" role="alert">
      {{ errorMessage }}
    </div>

    <!-- Books Grid -->
    <div v-else-if="books.length > 0" class="row">
      <div 
        v-for="book in books" 
        :key="book.id" 
        class="col-lg-3 col-md-4 col-sm-6 mb-4"
      >
        <div class="card book-card h-100">
          <!-- Book Cover -->
          <div class="book-cover-container">
            <img 
              :src="book.book_cover || PLACEHOLDER_IMAGE" 
              :alt="book.title || 'Cover Buku'"
              class="card-img-top book-cover-img"
              @error="handleImageError"
            />
          </div>

          <!-- Card Body -->
          <div class="card-body d-flex flex-column">
            <h5 class="card-title text-truncate" :title="book.title">
              {{ book.title }}
            </h5>

            <p class="card-text mb-2">
              <small class="text-muted d-block">Penulis:</small>
              <span class="text-truncate d-block text-dark fw-medium" :title="book.author">
                {{ book.author || '-' }}
              </span>
            </p>

            <p class="card-text mb-2">
              <small class="text-muted d-block">Kategori:</small>
              <span class="badge bg-secondary">
                {{ book.category?.name || 'Tanpa Kategori' }}
              </span>
            </p>

            <p class="card-text mb-3">
              <small class="text-muted d-block">Stok:</small>
              <span 
                class="badge" 
                :class="book.stock > 0 ? 'bg-success' : 'bg-danger'"
              >
                {{ book.stock ?? 0 }} unit
              </span>
            </p>

            <!-- Action Buttons -->
            <div class="mt-auto">
              <div class="btn-group w-100" role="group">
                <router-link 
                  :to="{ name: 'BookDetails', params: { id: book.id } }" 
                  class="btn btn-info btn-sm text-white"
                >
                  Detail
                </router-link>
                <router-link 
                  :to="{ name: 'BookEdit', params: { id: book.id } }" 
                  class="btn btn-warning btn-sm"
                >
                  Edit
                </router-link>
                <button 
                  @click="deleteBook(book.id)" 
                  class="btn btn-danger btn-sm"
                  :disabled="deletingId === book.id"
                >
                  {{ deletingId === book.id ? '...' : 'Hapus' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- No Books Found -->
    <div v-else class="text-center py-5 bg-light rounded-3">
      <h4 class="text-muted fw-bold">Tidak ada buku ditemukan</h4>
      <p class="text-muted mb-0">
        {{ searchQuery ? 'Coba ubah kata kunci pencarian Anda.' : 'Mulai dengan menambahkan buku baru.' }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../services/api';

const PLACEHOLDER_IMAGE = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjM2MCIgdmlld0JveD0iMCAwIDMwMCAzNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMzYwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMjAgMTIwSDEwNVYxMDVIMTIwVjEyMFpNMTk1IDEyMEgxODBWMTA1SDE5NVYxMjBaTTE5NSAxOTVIMTgwVjE4MEgxOTVWMTk1Wk0xMjAgMTk1SDEwNVYxODBIMTIwVjE5NVoiIGZpbGw9IiM5Q0E0QUYiLz4KPHRleHQgeD0iNTAlIiB5PSI1MCUiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiM2MzczODEiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiI+Tm8gSW1hZ2U8L3RleHQ+Cjwvc3ZnPg==';

// State
const books = ref([]);
const searchQuery = ref('');
const isLoading = ref(true);
const errorMessage = ref('');
const deletingId = ref(null);

let searchTimeout = null;

const fetchBooks = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    const response = await api.get('/books');
    books.value = response.data;
  } catch (error) {
    console.error('Error fetching books:', error);
    errorMessage.value = 'Gagal memuat daftar buku. Silakan coba lagi nanti.';
  } finally {
    isLoading.value = false;
  }
};

const handleSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout);

  searchTimeout = setTimeout(async () => {
    isLoading.value = true;
    errorMessage.value = '';

    try {
      if (!searchQuery.value.trim()) {
        await fetchBooks();
        return;
      }

      const response = await api.get('/books/search', {
        params: { query: searchQuery.value }
      });
      books.value = response.data;
    } catch (error) {
      console.error('Error searching books:', error);
      errorMessage.value = 'Gagal melakukan pencarian buku.';
    } finally {
      isLoading.value = false;
    }
  }, 300);
};

const deleteBook = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) return;

  deletingId.value = id;
  try {
    await api.delete(`/books/${id}`);
    // Hapus data secara lokal tanpa perlu memanggil API fetchBooks() ulang
    books.value = books.value.filter(book => book.id !== id);
  } catch (error) {
    console.error('Error deleting book:', error);
    alert('Gagal menghapus buku. Silakan coba lagi.');
  } finally {
    deletingId.value = null;
  }
};

const handleImageError = (event) => {
  if (event.target.src !== PLACEHOLDER_IMAGE) {
    event.target.src = PLACEHOLDER_IMAGE;
  }
};

onMounted(() => {
  fetchBooks();
});
</script>

<style scoped>
.book-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border: none;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
}

.badge {
  font-size: 0.8rem;
}

.btn-group .btn {
  font-size: 0.8rem;
  padding: 0.375rem 0.5rem;
}

@media (max-width: 576px) {
  .book-cover-container {
    height: 200px;
  }
}
</style>