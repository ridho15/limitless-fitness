# Test Manual API Users

## Cara Testing API Users

### 1. Pastikan Server Laravel Berjalan
```bash
php artisan serve
```

### 2. Test API Users dengan Pagination

#### A. Get All Users (Default Pagination)
```
GET http://127.0.0.1:8000/api/users
```

#### B. Get Users dengan Parameter
```
GET http://127.0.0.1:8000/api/users?page=1&per_page=10&search=john
```

#### C. Get Users dengan Filter Role
```
GET http://127.0.0.1:8000/api/users?role=Customer&status=active
```

#### D. Get User Detail
```
GET http://127.0.0.1:8000/api/user/1/detail
```

### 3. Test menggunakan cURL

#### A. Get Users dengan Pagination
```bash
curl -X GET "http://127.0.0.1:8000/api/users?page=1&per_page=5" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json"
```

#### B. Get Users dengan Search
```bash
curl -X GET "http://127.0.0.1:8000/api/users?search=admin" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json"
```

#### C. Get User Detail
```bash
curl -X GET "http://127.0.0.1:8000/api/user/1/detail" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json"
```

### 4. Test menggunakan PowerShell

#### A. Get Users
```powershell
$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/users" -Method Get
$response | ConvertTo-Json -Depth 4
```

#### B. Get User Detail
```powershell
$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/user/1/detail" -Method Get
$response | ConvertTo-Json -Depth 4
```

## Expected Response Features

### Users List Response:
- **Pagination**: Complete pagination information
- **Filtering**: Search by name/email, filter by role/status
- **Data**: Essential user information without sensitive data
- **Performance**: Efficient queries with proper relationships

### User Detail Response:
- **Complete Info**: All user fields including profile data
- **Statistics**: User activity statistics (packages, bookings, attendance)
- **Recent Activities**: Latest packages and bookings
- **Face Data**: Selfie and image data availability

## Query Parameters

### /users endpoint:
- `page`: Page number (integer, min: 1)
- `per_page`: Items per page (integer, min: 1, max: 100)
- `search`: Search term for name or email (string)
- `role`: Filter by role name (string)
- `status`: Filter by status - active/inactive (string)

### Validation Rules:
- All parameters are optional
- Invalid parameters return 422 validation error
- Non-existent user ID returns 404 error

## Performance Notes
- Users query includes role relationships
- User detail includes multiple relationships (roles, orderPaket, bookingKelas)
- Pagination limits prevent memory issues
- Statistics are calculated efficiently using collection methods
