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
