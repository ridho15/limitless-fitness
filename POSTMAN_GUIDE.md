# 📮 Postman API Collection - Limitless Fitness

## 📥 Import Instructions

### 1. Import Collection
1. Buka Postman
2. Klik **Import** button
3. Pilih file `Limitless-Fitness-API.postman_collection.json`
4. Klik **Import**

### 2. Import Environment
1. Klik **Environments** tab di sidebar kiri
2. Klik **Import** button
3. Pilih file `Limitless-Fitness-Environment.postman_environment.json`
4. Klik **Import**
5. Set environment sebagai **active** dengan mengklik environment name

---

## 🔧 Setup & Configuration

### Environment Variables
Setelah import, pastikan environment variables sudah di-set:

| Variable | Value | Description |
|----------|-------|-------------|
| `base_url` | `http://127.0.0.1:8000` | Development server URL |
| `user_id` | `1` | Default test user ID |
| `auth_token` | `your-token-here` | Bearer token untuk endpoint yang dilindungi |
| `test_face_image` | `data:image/jpeg;base64,...` | Sample base64 image untuk testing |

### Update Base URL
Untuk production environment:
1. Klik environment "Limitless Fitness Environment"
2. Update `base_url` dari `http://127.0.0.1:8000` ke production URL
3. Save changes

---

## 📋 Collection Structure

### 👥 User Management (2 endpoints)
- **Get Users (Paginated)**: Ambil daftar users dengan pagination dan filter
- **Get User Detail**: Ambil detail lengkap user termasuk statistik

### 📸 Face Management (4 endpoints)
- **Get User Selfie**: Ambil gambar selfie user tertentu
- **Get All User Selfies**: Ambil gambar selfie semua user
- **Register Face**: Daftarkan/update gambar wajah user
- **Validate Face**: Validasi wajah user dengan gambar referensi

### 📦 Package Management (1 endpoint)
- **Check User Package**: Cek paket aktif user berdasarkan order_paket (status=1, is_active=1, belum expired)

### 📁 Data Import (3 endpoints)
- **Import Users**: Import data user dari Excel
- **Import User Packages**: Import data paket user dari Excel
- **Import Class Bookings**: Import data booking kelas dari Excel

### 🔧 Utilities (2 endpoints)
- **Send Test Email**: Kirim email test (perlu authentication)
- **Get Authenticated User**: Ambil data user yang sedang login (perlu token Sanctum)

---

## 🧪 Testing Guide

### 1. Basic Testing Flow
1. **Health Check**: Test konektivitas API
2. **Get Users**: Pastikan ada data users
3. **Get User Detail**: Test dengan user_id yang valid
4. **Check Package**: Test pengecekan paket user

### 2. Face Registration Flow
1. **Register Face**: Upload gambar wajah user
2. **Validate Face**: Test validasi dengan gambar yang sama
3. **Get User Selfie**: Verifikasi gambar tersimpan

### 3. Authentication Testing
Untuk endpoint yang memerlukan auth:
1. Login melalui web interface
2. Dapatkan bearer token
3. Set `auth_token` variable di environment
4. Test endpoint yang memerlukan authentication

---

## 🔍 Request Examples

### User Management
```javascript
// Get users with pagination and search
GET {{base_url}}/api/users?page=1&per_page=20&search=john&role=Customer&status=active

// Get detailed user information
GET {{base_url}}/api/user/{{user_id}}/detail
```

### Face Management
```javascript
// Register face
POST {{base_url}}/api/register-face
{
    "user_id": {{user_id}},
    "face_image": "{{test_face_image}}",
    "image_type": "img_base64"
}

// Validate face
POST {{base_url}}/api/validate-face
{
    "user_id": {{user_id}},
    "face_image": "{{test_face_image}}"
}
```

### Package Management
```javascript
// Check user packages (mengacu pada order_paket)
POST {{base_url}}/api/check-paket
{
    "id_user": {{user_id}}
}

// Response akan mencakup informasi tambahan:
// - check_method: "order_paket_based" 
// - order_status dan is_active dari order_paket
// - note tentang metode pengecekan
```

---

## ⚙️ Important Logic Changes

### Package Checking Logic
Karena **kelas_paket belum aktif pada server live**, sistem sekarang menggunakan logika berikut:

#### Kondisi Paket Aktif:
1. **status = true (1)** - Order paket sudah dikonfirmasi
2. **is_active = true (1)** - Order paket masih aktif
3. **expired_date >= hari ini** - Paket belum expired

#### Response Format:
- **Success**: Mengembalikan data paket aktif dengan info `check_method: "order_paket_based"`
- **No Active Package**: Membedakan antara user yang tidak punya order sama sekali vs yang punya order tapi sudah expired/inactive

#### Keuntungan Pendekatan Ini:
- ✅ Tetap bisa cek status paket meski kelas_paket belum ready
- ✅ Data akurat berdasarkan order yang sudah dikonfirmasi
- ✅ Informasi detail tentang remaining days dan status
- ✅ Backward compatible ketika kelas_paket nanti diaktifkan

---

## 📊 Response Validation

### Auto-tests Included
Collection sudah dilengkapi dengan auto-tests yang akan:
- ✅ Validasi status code
- ✅ Cek struktur response (status, message fields)
- ✅ Log response time
- ✅ Log response status

### Manual Validation Checklist
- [ ] Response time < 2000ms
- [ ] Status field bernilai boolean
- [ ] Message field berisi string
- [ ] Data field sesuai struktur yang diharapkan
- [ ] Pagination info lengkap (untuk endpoint paginated)

---

## 🚨 Troubleshooting

### Common Issues

#### 1. Connection Refused
```
Error: connect ECONNREFUSED 127.0.0.1:8000
```
**Solution**: Pastikan Laravel server running
```bash
php artisan serve
```

#### 2. 404 Not Found
```
{
    "message": "Page Not Found."
}
```
**Solution**: 
- Cek URL endpoint
- Pastikan routes sudah didefinisikan
- Clear route cache: `php artisan route:clear`

#### 3. 422 Validation Error
```json
{
    "status": false,
    "message": "Validasi gagal",
    "errors": {
        "user_id": ["Data user belum dikirim"]
    }
}
```
**Solution**: Cek request body sesuai dengan requirement

#### 4. 500 Internal Server Error
**Solution**: 
- Cek Laravel logs: `storage/logs/laravel.log`
- Cek database connection
- Pastikan semua dependencies terinstall

### Debug Steps
1. **Check Server Status**:
   ```bash
   php artisan serve --host=127.0.0.1 --port=8000
   ```

2. **Clear Cache**:
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan cache:clear
   ```

3. **Check Routes**:
   ```bash
   php artisan route:list --path=api
   ```

4. **Check Logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## 🌟 Advanced Usage

### Custom Scripts
Anda bisa menambahkan custom scripts untuk:

#### Pre-request Scripts
```javascript
// Auto-set timestamp
pm.globals.set('timestamp', new Date().toISOString());

// Auto-generate test data
pm.globals.set('random_email', 'test' + Math.random() + '@example.com');
```

#### Test Scripts
```javascript
// Extract data from response
const jsonData = pm.response.json();
if (jsonData.status && jsonData.data && jsonData.data.user_id) {
    pm.environment.set('last_user_id', jsonData.data.user_id);
}

// Chain requests
if (pm.response.code === 200) {
    pm.setNextRequest('Get User Detail');
}
```

### Bulk Testing
Untuk test semua endpoints sekaligus:
1. Klik **Collection** name
2. Klik **Run**
3. Pilih requests yang ingin di-test
4. Klik **Run Limitless Fitness API Collection**

---

## 📝 Notes

### Rate Limiting
- Development: Tidak ada rate limiting
- Production: Mungkin ada batasan request per menit

### Authentication
- Beberapa endpoint memerlukan Bearer token
- Token didapat dari login web interface
- Set token di environment variable `auth_token`

### File Upload Testing
Untuk endpoints import:
1. Prepare Excel file sesuai format yang diperlukan
2. Select file di request body
3. Ensure file size < 10MB

### Base64 Images
Untuk testing face registration/validation:
- Gunakan variable `test_face_image`
- Atau generate base64 dari gambar real
- Format: `data:image/jpeg;base64,{base64-string}`

---

**Created**: August 31, 2025  
**Version**: 1.0.0  
**For**: Limitless Fitness API v1.0
