# 📦 Package Check Logic - Limitless Fitness API

## 🎯 Overview
Dokumen ini menjelaskan logika pengecekan paket aktif user pada API endpoint `/api/check-paket`.

## ⚠️ Current Situation
**Kelas paket belum aktif pada server live**, sehingga sistem menggunakan pendekatan alternatif untuk tetap dapat mengecek status paket user.

## 🔄 Logic Implementation

### Kondisi yang Dicek:
```sql
SELECT * FROM order_paket 
WHERE id_user = ? 
  AND status = 1           -- Order sudah dikonfirmasi
  AND is_active = 1        -- Order masih aktif
  AND expired_date >= NOW() -- Belum expired
```

### Flow Diagram:
```
User Request (id_user)
        ↓
Query order_paket table
        ↓
Check conditions:
├── status = 1? ✓
├── is_active = 1? ✓
└── expired_date >= today? ✓
        ↓
Found active packages?
├── YES → Return active packages data
└── NO → Check if user has any orders
           ├── Has orders → "expired/inactive packages"
           └── No orders → "no packages at all"
```

## 📊 Response Structure

### Success Response (Has Active Packages):
```json
{
    "status": true,
    "message": "User memiliki paket aktif",
    "data": {
        "user_id": 1,
        "total_active_packages": 2,
        "packages": [
            {
                "order_paket_id": 789,
                "paket_id": 101,
                "paket_name": "Premium Monthly",
                "paket_type": "Monthly", 
                "paket_durasi": 30,
                "expired_date": "2025-12-31",
                "status": "Active",
                "remaining_days": 133,
                "created_at": "2025-08-01T10:00:00Z",
                "order_status": 1,      // NEW: Original status dari order_paket
                "is_active": 1          // NEW: Original is_active dari order_paket
            }
        ],
        "check_method": "order_paket_based",                           // NEW: Info metode pengecekan
        "note": "Pengecekan berdasarkan order_paket karena kelas_paket belum aktif" // NEW: Penjelasan
    }
}
```

### No Active Packages Response:
```json
{
    "status": false,
    "message": "User tidak memiliki paket aktif (ada order tapi sudah expired atau inactive)",
    "data": {
        "user_id": 1,
        "total_active_packages": 0,
        "has_any_orders": true,         // NEW: Info apakah user pernah punya order
        "check_method": "order_paket_based"
    }
}
```

## 🔧 Technical Details

### Database Fields Used:
- `order_paket.id_user` - ID user yang memiliki paket
- `order_paket.status` - Status konfirmasi order (1=confirmed, 0=pending/cancelled)
- `order_paket.is_active` - Status aktif order (1=active, 0=inactive) 
- `order_paket.expired_date` - Tanggal kadaluarsa paket
- `order_paket.id_paket` - Referensi ke master paket
- `paket.nama` - Nama paket
- `paket.tipe` - Tipe paket (Monthly, Weekly, etc.)
- `paket.durasi` - Durasi paket dalam hari

### Controller Method:
- **File**: `app/Http/Controllers/Api/PaketController.php`
- **Method**: `checkPaket(Request $request)`
- **Validation**: Memastikan `id_user` valid dan exists di database

## 🚀 Future Migration Plan

Ketika **kelas_paket sudah aktif** di server live, logika dapat diupdate untuk:

1. **Cek kelas_paket terlebih dahulu**:
   ```sql
   SELECT * FROM kelas_paket 
   WHERE id_user = ? AND status_aktif = 1 AND expired_date >= NOW()
   ```

2. **Fallback ke order_paket** jika tidak ada kelas_paket:
   ```sql
   SELECT * FROM order_paket 
   WHERE id_user = ? AND status = 1 AND is_active = 1 AND expired_date >= NOW()
   ```

3. **Response akan include**:
   ```json
   {
       "check_method": "kelas_paket_primary", // atau "order_paket_fallback"
       "data_source": "kelas_paket" // atau "order_paket"
   }
   ```

## 📝 Testing Scenarios

### Test Case 1: User dengan paket aktif
```bash
curl -X POST http://127.0.0.1:8000/api/check-paket \
  -H "Content-Type: application/json" \
  -d '{"id_user": 1}'
```
**Expected**: Status true, data paket aktif, remaining_days > 0

### Test Case 2: User dengan paket expired
```bash
curl -X POST http://127.0.0.1:8000/api/check-paket \
  -H "Content-Type: application/json" \
  -d '{"id_user": 2}'
```
**Expected**: Status false, message menyebutkan expired/inactive

### Test Case 3: User tanpa order sama sekali
```bash
curl -X POST http://127.0.0.1:8000/api/check-paket \
  -H "Content-Type: application/json" \
  -d '{"id_user": 999}'
```
**Expected**: Status false, message menyebutkan tidak ada order

## 📊 Monitoring & Analytics

### Metrics to Track:
- **Active Package Rate**: Persentase user dengan paket aktif
- **Package Distribution**: Jenis paket yang paling banyak digunakan
- **Expiry Pattern**: Pola expired date untuk prediksi renewal
- **Response Time**: Performance endpoint check-paket

### Log Information:
Controller akan log informasi berikut:
- User ID yang dicek
- Jumlah paket aktif ditemukan
- Metode pengecekan yang digunakan
- Response time

---

**Last Updated**: August 31, 2025  
**Version**: 1.1.0  
**Status**: Production Ready ✅
