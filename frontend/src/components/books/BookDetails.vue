<template>
  <div class="container mt-5">
    <h2 class="mb-4">Detail Buku</h2>

    <!-- State Loading -->
    <div v-if="isLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Memuat...</span>
      </div>
      <p class="mt-2 text-muted">Memuat data buku...</p>
    </div>

    <!-- State Error -->
    <div v-else-if="errorMessage" class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
      <span>{{ errorMessage }}</span>
      <router-link :to="{ name: 'BookList' }" class="btn btn-outline-danger btn-sm">
        Kembali ke Daftar
      </router-link>
    </div>

    <!-- Content Card -->
    <div v-else-if="book" class="card shadow-sm border-0 rounded-3 overflow-hidden">
      <div class="row g-0">
        <div class="col-md-4 bg-light d-flex align-items-center justify-content-center p-3">
          <div class="card-img-container">
            <img 
              :src="book.book_cover || PLACEHOLDER_IMAGE" 
              :alt="book.title || 'Cover Buku'"
              class="card-img book-cover-image"
              @error="handleImageError"
            />
          </div>
        </div>

        <div class="col-md-8">
          <div class="card-body p-4 d-flex flex-column h-100 justify-content-between">
            <div>
              <h3 class="card-title text-dark fw-bold mb-3">{{ book.title }}</h3>

              <div class="book-details mb-4">
                <div class="detail-item py-2 border-bottom">
                  <span class="text-muted fw-semibold">Penulis:</span>
                  <span class="ms-2 text-dark">{{ book.author || '-' }}</span>
                </div>
                <div class="detail-item py-2 border-bottom">
                  <span class="text-muted fw-semibold">Penerbit:</span>
                  <span class="ms-2 text-dark">{{ book.publisher || '-' }}</span>
                </div>
                <div class="detail-item py-2 border-bottom">
                  <span class="text-muted fw-semibold">Kategori:</span>
                  <span class="badge bg-info text-dark ms-2">
                    {{ book.category?.name || 'Tanpa Kategori' }}
                  </span>
                </div>
                <div class="detail-item py-2 border-bottom">
                  <span class="text-muted fw-semibold">Stok:</span>
                  <span 
                    class="badge ms-2"
                    :class="book.stock > 0 ? 'bg-success' : 'bg-danger'"
                  >
                    {{ book.stock ?? 0 }} unit
                  </span>
                </div>
              </div>
            </div>

            <div>
              <router-link :to="{ name: 'BookList' }" class="btn btn-secondary px-4">
                Kembali
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../../services/api';

const PLACEHOLDER_IMAGE = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjM2MCIgdmlld0JveD0iMCAwIDMwMCAzNjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMzYwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMjAgMTIwSDEwNVYxMDVIMTIwVjEyMFpNMTk1IDEyMEgxODBWMTA1SDE5NVYxMjBaTTE5NSAxOTVIMTgwVjE4MEgxOTVWMTk1Wk0xMjAgMTk1SDEwNVYxODBIMTIwVjE5NVoiIGZpbGw9IiM5Q0E0QUYiLz4KPHRleHQgeD0iNTAlIiB5PSI1MCUiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiM2MzczODEiIGZvbnQtc2l6ZT0iMTYiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiI+Tm8gSW1hZ2U8L3RleHQ+Cjwvc3ZnPg==';

const route = useRoute();

// State
const book = ref(null);
const isLoading = ref(true);
const errorMessage = ref('');

const fetchBook = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    const response = await api.get(`/books/${route.params.id}`);
    book.value = response.data;
  } catch (error) {
    console.error('Error fetching book:', error);
    errorMessage.value = error.response?.data?.message || 'Gagal memuat detail buku. Buku mungkin tidak ditemukan.';
  } finally {
    isLoading.value = false;
  }
};

const handleImageError = (event) => {
  // Cegah infinite loop jika placeholder image juga gagal dimuat
  if (event.target.src !== PLACEHOLDER_IMAGE) {
    event.target.src = PLACEHOLDER_IMAGE;
  }
};

onMounted(() => {
  fetchBook();
});
</script>

<style scoped>
.card-img-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 280px;
}

.book-cover-image {
  max-width: 100%;
  max-height: 380px;
  width: auto;
  height: auto;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
  transition: transform 0.3s ease;
}

.book-cover-image:hover {
  transform: scale(1.03);
}

.detail-item {
  font-size: 1rem;
}

@media (max-width: 768px) {
  .card-img-container {
    min-height: 200px;
  }
  
  .book-cover-image {
    max-height: 250px;
  }
}
</style>