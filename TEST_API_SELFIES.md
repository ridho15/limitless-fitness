# Test Manual API User Selfies

## Cara Testing Manual

### 1. Pastikan Server Laravel Berjalan
```bash
php artisan serve
```

### 2. Test menggunakan Browser atau Postman

#### A. Test Get All User Selfies
```
GET http://127.0.0.1:8000/api/user-selfies
```

#### B. Test Get Specific User Selfie
```
GET http://127.0.0.1:8000/api/user-selfie/1
```

#### C. Test User Not Found
```
GET http://127.0.0.1:8000/api/user-selfie/999
```

### 3. Test menggunakan cURL

#### A. Get All Users
```bash
curl -X GET "http://127.0.0.1:8000/api/user-selfies" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json"
```

#### B. Get Specific User
```bash
curl -X GET "http://127.0.0.1:8000/api/user-selfie/1" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json"
```

### 4. Test menggunakan PowerShell

#### A. Get All Users
```powershell
$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/user-selfies" -Method Get
$response | ConvertTo-Json -Depth 3
```

#### B. Get Specific User
```powershell
$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/user-selfie/1" -Method Get
$response | ConvertTo-Json -Depth 3
```

## Expected Results

### Success Response Format
- **status**: true/false
- **data**: object/array containing user selfie information
- **message**: error message (only when status is false)

### Error Scenarios
- User ID tidak ditemukan: HTTP 404 dengan message "User tidak ditemukan"
- Server error: HTTP 500 dengan error message

## API Implementation Status
✅ Routes added to `/routes/api.php`
✅ Controller methods implemented in `HelperController`
✅ Documentation updated in `API.md`
✅ Error handling implemented
✅ JSON response format standardized
