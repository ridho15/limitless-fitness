# API Documentation - Limitless Fitness

## Base Information
- **Base URL**: `https://your-domain.com/api`
- **Authentication**: Laravel Sanctum
- **Content-Type**: `application/json` (for JSON requests)
- **Content-Type**: `multipart/form-data` (for file uploads)

## Authentication

### Get Authenticated User
```http
GET /user
```

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    // ... other user properties
}
```

## API Endpoints

### 1. Send Email
Send test confirmation email.

```http
GET /send-email
```

**Response:**
```json
{
    "status": true,
    "message": "Berhasil"
}
```

---

### 2. Import Users
Import users from Excel file.

```http
POST /import-user
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| data | file | Yes | Excel file containing user data |

**Response Success:**
```json
{
    "status": true,
    "message": "Berhasil import data"
}
```

**Response Error:**
```json
{
    "status": false,
    "message": "Data tidak terdeteksi"
}
```

---

### 3. Import User Packages
Import user package orders from Excel file.

```http
POST /import-user-paket
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| data | file | Yes | Excel file containing user package data |

**Response Success:**
```json
{
    "status": true,
    "message": "Berhasil import data"
}
```

**Response Error:**
```json
{
    "status": false,
    "message": "Error message"
}
```

---

### 4. Import Class Bookings
Import class bookings from Excel file.

```http
POST /import-booking-kelas
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| data | file | Yes | Excel file containing class booking data |

**Response Success:**
```json
{
    "status": true,
    "message": "Berhasil import data"
}
```

**Response Error:**
```json
{
    "status": false,
    "message": "Error message"
}
```

---

### 5. Check Package Validity
Check if user has valid package for specific class.

```http
POST /check-paket
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id_user | integer | Yes | User ID |
| id_kelas | integer | Yes | Class ID |

**Request Example:**
```json
{
    "id_user": 123,
    "id_kelas": 456
}
```

**Response Success:**
```json
{
    "status": true,
    "message": "Paket tersedia dan sesuai dengan kelas",
    "data": {
        "order_paket_id": 789,
        "user_id": 123,
        "paket_id": 101,
        "paket_name": "Premium Package",
        "expired_date": "2025-12-31",
        "status": "Active",
        "remaining_days": 133
    }
}
```

**Response Error - No Valid Package:**
```json
{
    "status": false,
    "message": "Tidak ada paket aktif yang sesuai dengan kelas ini"
}
```

**Response Error - Validation:**
```json
{
    "status": false,
    "message": "Validasi gagal",
    "errors": {
        "id_user": ["Data user belum dikirim"],
        "id_kelas": ["Data kelas belum dikirim"]
    }
}
```

---

### 6. Face Registration
Register or update user's face image for validation.

```http
POST /register-face
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | Yes | User ID |
| face_image | string | Yes | Base64 encoded face image |
| image_type | string | No | Type of image field to update (`selfie` or `img_base64`). Default: `img_base64` |

**Request Example:**
```json
{
    "user_id": 123,
    "face_image": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...",
    "image_type": "img_base64"
}
```

**Response Success:**
```json
{
    "status": true,
    "message": "Registrasi wajah berhasil",
    "data": {
        "user_id": 123,
        "user_name": "John Doe",
        "image_type": "img_base64",
        "registered_at": "2025-08-20T10:30:00.000000Z"
    }
}
```

**Response Error - Invalid Image Format:**
```json
{
    "status": false,
    "message": "Format gambar tidak valid. Gunakan format base64 yang benar."
}
```

**Response Error - Validation:**
```json
{
    "status": false,
    "message": "Validasi gagal",
    "errors": {
        "user_id": ["Data user belum dikirim"],
        "face_image": ["Gambar wajah belum dikirim"]
    }
}
```

---

### 7. Face Validation
Validate user face against stored reference image.

```http
POST /validate-face
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | Yes | User ID |
| face_image | string | Yes | Base64 encoded face image |

**Request Example:**
```json
{
    "user_id": 123,
    "face_image": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD..."
}
```

**Response Success:**
```json
{
    "status": true,
    "data": {
        "user_id": 123,
        "user_name": "John Doe",
        "is_valid": true,
        "similarity_score": 0.895,
        "threshold": 0.8,
        "message": "Validasi wajah berhasil"
    }
}
```

**Response Failed Validation:**
```json
{
    "status": true,
    "data": {
        "user_id": 123,
        "user_name": "John Doe",
        "is_valid": false,
        "similarity_score": 0.652,
        "threshold": 0.8,
        "message": "Validasi wajah gagal"
    }
}
```

**Response Error - User Not Found:**
```json
{
    "status": false,
    "message": "User tidak ditemukan"
}
```

**Response Error - No Reference Image:**
```json
{
    "status": false,
    "message": "Data wajah referensi tidak ditemukan"
}
```

---

### 8. Get User Selfie
Get selfie image data for a specific user.

```http
GET /user-selfie/{id}
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | User ID (URL parameter) |

**Response Success:**
```json
{
    "status": true,
    "data": {
        "user_id": 123,
        "user_name": "John Doe",
        "selfie": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...",
        "img_base64": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD..."
    }
}
```

**Response Error - User Not Found:**
```json
{
    "status": false,
    "message": "User tidak ditemukan"
}
```

---

### 9. Get All User Selfies
Get selfie image data for all users.

```http
GET /user-selfies
```

**Response Success:**
```json
{
    "status": true,
    "data": [
        {
            "user_id": 123,
            "user_name": "John Doe",
            "selfie": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...",
            "img_base64": null
        },
        {
            "user_id": 124,
            "user_name": "Jane Smith",
            "selfie": null,
            "img_base64": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD..."
        }
    ]
}
```

---

### 10. Get Users with Pagination
Get list of users with pagination and filtering support.

```http
GET /users
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| page | integer | No | Page number (default: 1) |
| per_page | integer | No | Items per page (default: 15, max: 100) |
| search | string | No | Search by name or email |
| role | string | No | Filter by user role |
| status | string | No | Filter by status (`active` or `inactive`) |

**Request Example:**
```
GET /api/users?page=1&per_page=10&search=john&role=Customer&status=active
```

**Response Success:**
```json
{
    "status": true,
    "message": "Data users berhasil diambil",
    "data": [
        {
            "id": 123,
            "name": "John Doe",
            "email": "john@example.com",
            "barcode": "12345678",
            "phone": "081234567890",
            "alamat": "Jl. Example No. 123",
            "is_verified": true,
            "has_selfie": true,
            "has_img_base64": false,
            "roles": ["Customer"],
            "created_at": "2025-01-01T10:00:00.000000Z",
            "updated_at": "2025-01-15T15:30:00.000000Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 10,
        "total": 50,
        "from": 1,
        "to": 10,
        "has_more_pages": true,
        "next_page_url": "http://example.com/api/users?page=2",
        "prev_page_url": null
    }
}
```

---

### 11. Get User Detail
Get comprehensive user details including statistics and recent activities.

```http
GET /user/{id}/detail
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | User ID (URL parameter) |

**Response Success:**
```json
{
    "status": true,
    "message": "Detail user berhasil diambil",
    "data": {
        "id": 123,
        "name": "John Doe",
        "email": "john@example.com",
        "barcode": "12345678",
        "phone": "081234567890",
        "alamat": "Jl. Example No. 123",
        "tanggal_lahir": "1990-01-01",
        "jenis_kelamin": "L",
        "is_verified": true,
        "email_verified_at": "2025-01-01T10:00:00.000000Z",
        "has_selfie": true,
        "has_img_base64": false,
        "selfie_url": "data:image/jpeg;base64,/9j/4AAQ...",
        "img_base64_url": null,
        "roles": [
            {
                "id": 1,
                "name": "Customer",
                "guard_name": "web"
            }
        ],
        "statistics": {
            "total_order_paket": 5,
            "active_paket": 2,
            "total_bookings": 25,
            "attended_bookings": 20,
            "missed_bookings": 3,
            "attendance_rate": 80.0
        },
        "recent_packages": [
            {
                "id": 789,
                "paket_name": "Premium Package",
                "status": 1,
                "is_active": true,
                "expired_date": "2025-12-31",
                "created_at": "2025-06-01T10:00:00.000000Z"
            }
        ],
        "recent_bookings": [
            {
                "id": 456,
                "kelas_name": "Yoga",
                "tanggal": "2025-08-30",
                "jam_mulai": "08:00:00",
                "jam_selesai": "09:30:00",
                "status_kehadiran": 1,
                "keterangan": null,
                "created_at": "2025-08-25T14:00:00.000000Z"
            }
        ],
        "created_at": "2025-01-01T10:00:00.000000Z",
        "updated_at": "2025-08-29T15:30:00.000000Z"
    }
}
```

**Response Error - User Not Found:**
```json
{
    "status": false,
    "message": "User tidak ditemukan"
}
```

---

### 10. Get Users with Pagination
Get list of users with pagination and filtering options.

```http
GET /users
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| page | integer | No | Page number (default: 1) |
| per_page | integer | No | Items per page (default: 15, max: 100) |
| search | string | No | Search by name or email |
| role | string | No | Filter by user role |
| status | string | No | Filter by status (`active` or `inactive`) |

**Request Example:**
```
GET /users?page=1&per_page=20&search=john&role=Customer&status=active
```

**Response Success:**
```json
{
    "status": true,
    "message": "Data users berhasil diambil",
    "data": [
        {
            "id": 123,
            "name": "John Doe",
            "email": "john@example.com",
            "barcode": "12345678",
            "phone": "+6281234567890",
            "alamat": "Jl. Example No. 123",
            "is_verified": true,
            "has_selfie": true,
            "has_img_base64": false,
            "roles": ["Customer"],
            "created_at": "2025-08-20T10:00:00Z",
            "updated_at": "2025-08-20T10:00:00Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 20,
        "total": 95,
        "from": 1,
        "to": 20,
        "has_more_pages": true,
        "next_page_url": "http://localhost:8000/api/users?page=2",
        "prev_page_url": null
    }
}
```

---

### 11. Get User Detail
Get detailed information about a specific user including statistics and recent activities.

```http
GET /user/{id}/detail
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | User ID (URL parameter) |

**Response Success:**
```json
{
    "status": true,
    "message": "Detail user berhasil diambil",
    "data": {
        "id": 123,
        "name": "John Doe",
        "email": "john@example.com",
        "barcode": "12345678",
        "phone": "+6281234567890",
        "alamat": "Jl. Example No. 123",
        "tanggal_lahir": "1990-01-15",
        "jenis_kelamin": "L",
        "is_verified": true,
        "email_verified_at": "2025-08-20T10:00:00Z",
        "has_selfie": true,
        "has_img_base64": false,
        "selfie_url": "data:image/jpeg;base64,/9j/4AAQ...",
        "img_base64_url": null,
        "roles": [
            {
                "id": 2,
                "name": "Customer",
                "guard_name": "web"
            }
        ],
        "statistics": {
            "total_order_paket": 5,
            "active_paket": 2,
            "total_bookings": 25,
            "attended_bookings": 20,
            "missed_bookings": 3,
            "attendance_rate": 80.0
        },
        "recent_packages": [
            {
                "id": 101,
                "paket_name": "Premium Monthly",
                "status": 1,
                "is_active": true,
                "expired_date": "2025-12-20",
                "created_at": "2025-08-20T10:00:00Z"
            }
        ],
        "recent_bookings": [
            {
                "id": 301,
                "kelas_name": "Yoga",
                "tanggal": "2025-08-25",
                "jam_mulai": "09:00:00",
                "jam_selesai": "10:30:00",
                "status_kehadiran": 1,
                "keterangan": null,
                "created_at": "2025-08-20T10:00:00Z"
            }
        ],
        "created_at": "2025-01-15T10:00:00Z",
        "updated_at": "2025-08-20T10:00:00Z"
    }
}
```

**Response Error - User Not Found:**
```json
{
    "status": false,
    "message": "User tidak ditemukan"
}
```

## Error Codes

| HTTP Status | Description |
|-------------|-------------|
| 200 | Success |
| 400 | Bad Request |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Internal Server Error |

## Data Models

### User
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "barcode": "12345678",
    "selfie": "base64_image_string",
    "img_base64": "base64_image_string"
}
```

### Order Paket
```json
{
    "id": 1,
    "id_user": 123,
    "id_paket": 456,
    "status": 1,
    "is_active": true,
    "expired_date": "2025-12-31",
    "created_at": "2025-08-20T10:00:00Z"
}
```

### Paket
```json
{
    "id": 1,
    "nama": "Premium Package",
    "valid_month": 6,
    "kelas": [
        {
            "id": 1,
            "nama_kelas": "Yoga",
            "max_anggota": 20
        }
    ]
}
```

### Face Registration Technical Details

### Image Requirements
- Format: JPEG, PNG
- Encoding: Base64 with proper data URI format (`data:image/jpeg;base64,` or `data:image/png;base64,`)
- Recommended size: Maximum 2MB
- Resolution: Minimum 300x300 pixels
- The system validates the image format before saving

### Registration Process
1. **Validation**: Image format and base64 encoding are validated
2. **Storage**: Image is stored in the user's record (`selfie` or `img_base64` field)
3. **Activity Logging**: Registration activity is logged for audit purposes
4. **Response**: Confirmation with user details and registration timestamp

### Image Storage Options
- **img_base64**: Default field for storing face images
- **selfie**: Alternative field for selfie images
- Users can specify which field to update using the `image_type` parameter

## Face Validation Technical Details

### Image Requirements
- Format: JPEG, PNG
- Encoding: Base64
- Recommended size: Maximum 2MB
- Resolution: Minimum 300x300 pixels

### Similarity Scoring
- Range: 0.0 to 1.0
- Threshold: 0.8 (configurable)
- Algorithm: Basic image feature comparison
- **Note**: This is a simplified implementation. For production use, consider implementing more advanced face recognition algorithms.

### Security Considerations
- Face validation uses basic image comparison
- Not suitable for high-security applications
- Consider implementing additional security measures for production

## Rate Limiting
API endpoints may be subject to rate limiting. Check response headers for rate limit information:
- `X-RateLimit-Limit`: Request limit
- `X-RateLimit-Remaining`: Remaining requests
- `X-RateLimit-Reset`: Rate limit reset time

## Support
For API support and questions, contact the development team or refer to the application documentation.
