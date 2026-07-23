<template>
  <div class="container mt-5">
    <h2 class="mb-4">Tambah Buku</h2>

    <form @submit.prevent="createBook">
      <div class="mb-3">
        <label for="title" class="form-label">Judul</label>
        <input type="text" class="form-control" id="title" v-model="book.title" required>
      </div>

      <div class="mb-3">
        <label for="author" class="form-label">Penulis</label>
        <input type="text" class="form-control" id="author" v-model="book.author" required>
      </div>

      <div class="mb-3">
        <label for="publisher" class="form-label">Penerbit</label>
        <input type="text" class="form-control" id="publisher" v-model="book.publisher" required>
      </div>

      <div class="mb-3">
        <label for="category" class="form-label">Kategori</label>
        <select class="form-select" id="category" v-model="book.category_id" required>
          <option value="" disabled>Pilih Kategori</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
      </div>

      <div class="mb-3">
        <label for="stock" class="form-label">Stok</label>
        <input type="number" class="form-control" id="stock" v-model.number="book.stock" required min="1" max="10">
      </div>

      <!-- Book Cover Upload -->
      <div class="mb-3">
        <label for="book_cover" class="form-label">Cover Buku</label>
        <input 
          ref="fileInputRef"
          type="file" 
          class="form-control" 
          id="book_cover" 
          @change="handleFileUpload"
          accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
        >
        <small class="form-text text-muted">Format yang didukung: JPEG, PNG, JPG, GIF, WebP. Maksimal 10MB.</small>

        <!-- Validation Error Message -->
        <div v-if="fileError" class="text-danger small mt-1">{{ fileError }}</div>

        <!-- Preview Image -->
        <div v-if="previewImage" class="mt-3">
          <div class="preview-container">
            <img :src="previewImage" alt="Preview" class="preview-image">
            <button type="button" class="btn btn-sm btn-danger remove-preview" @click="removePreview">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Server Error Alert -->
      <div v-if="errorMessage" class="alert alert-danger" role="alert">
        {{ errorMessage }}
      </div>

      <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
        <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-1" role="status"></span>
        {{ isSubmitting ? 'Menyimpan...' : 'Simpan' }}
      </button>
      <router-link :to="{ name: 'BookList' }" class="btn btn-secondary ms-2">Kembali</router-link>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../services/api';

const MAX_FILE_SIZE_MB = 10;
const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];

const router = useRouter();

// State Form
const book = reactive({
  title: '',
  author: '',
  publisher: '',
  category_id: '',
  stock: 1,
});

// State Pendukung
const categories = ref([]);
const bookCoverFile = ref(null);
const previewImage = ref(null);
const fileInputRef = ref(null);
const fileError = ref('');
const errorMessage = ref('');
const isSubmitting = ref(false);

const fetchCategories = async () => {
  try {
    const response = await api.get('/categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  fileError.value = '';

  if (!file) return;

  if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
    fileError.value = `File terlalu besar. Maksimal ${MAX_FILE_SIZE_MB}MB.`;
    resetFileInput();
    return;
  }

  if (!ALLOWED_MIME_TYPES.includes(file.type)) {
    fileError.value = 'Tipe file tidak didukung. Gunakan JPEG, PNG, JPG, GIF, atau WebP.';
    resetFileInput();
    return;
  }

  // Hapus memori blob lama jika user ganti gambar
  if (previewImage.value) {
    URL.revokeObjectURL(previewImage.value);
  }

  bookCoverFile.value = file;
  previewImage.value = URL.createObjectURL(file);
};

const removePreview = () => {
  if (previewImage.value) {
    URL.revokeObjectURL(previewImage.value);
  }
  bookCoverFile.value = null;
  previewImage.value = null;
  resetFileInput();
};

const resetFileInput = () => {
  if (fileInputRef.value) {
    fileInputRef.value.value = '';
  }
};

const createBook = async () => {
  isSubmitting.value = true;
  errorMessage.value = '';

  try {
    const formData = new FormData();

    Object.entries(book).forEach(([key, value]) => {
      formData.append(key, value);
    });

    if (bookCoverFile.value) {
      formData.append('book_cover', bookCoverFile.value);
    }

    await api.post('/books', formData);
    router.push({ name: 'BookList' });
  } catch (error) {
    console.error('Error creating book:', error);
    errorMessage.value = error.response?.data?.message || 'Gagal menyimpan buku. Silakan coba lagi.';
  } finally {
    isSubmitting.value = false;
  }
};

onMounted(() => {
  fetchCategories();
});

// Bersihkan objek memori browser saat komponen di-unmount
onBeforeUnmount(() => {
  if (previewImage.value) {
    URL.revokeObjectURL(previewImage.value);
  }
});
</script>

<style scoped>
.preview-container {
  position: relative;
  display: inline-block;
  max-width: 200px;
}

.preview-image {
  width: 100%;
  height: auto;
  max-height: 250px;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.remove-preview {
  position: absolute;
  top: -8px;
  right: -8px;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
}
</style>