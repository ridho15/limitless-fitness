# 📚 API Documentation - Limitless Fitness

## 📋 Table of Contents
1. [Overview](#overview)
2. [Authentication](#authentication)
3. [Base URL & Headers](#base-url--headers)
4. [Response Format](#response-format)
5. [Error Handling](#error-handling)
6. [User Management APIs](#user-management-apis)
7. [Face Management APIs](#face-management-apis)
8. [Package & Booking APIs](#package--booking-apis)
9. [Import & Utility APIs](#import--utility-apis)
10. [Rate Limiting](#rate-limiting)
11. [Examples](#examples)

---

## 🔍 Overview

The Limitless Fitness API provides endpoints for managing users, face recognition, package validation, and various administrative functions for a fitness center management system.

**Version**: 1.0  
**Last Updated**: August 29, 2025  
**Base Technology**: Laravel 10 + PHP 8.1+

---

## 🔐 Authentication

### Sanctum Token Authentication
Most endpoints require authentication using Laravel Sanctum tokens.

```http
Authorization: Bearer {your-token-here}
```

### Public Endpoints
Some endpoints are publicly accessible:
- Face validation
- Package checking
- Import functions
- User registration

---

## 🌐 Base URL & Headers

### Base URL
```
https://your-domain.com/api
```

### Required Headers
```http
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token} // For protected endpoints
```

### File Upload Headers
```http
Content-Type: multipart/form-data
Accept: application/json
```

---

## 📝 Response Format

All API responses follow a consistent JSON structure:

### Success Response
```json
{
    "status": true,
    "message": "Success message",
    "data": {
        // Response data
    },
    "pagination": {
        // Pagination info (if applicable)
    }
}
```

### Error Response
```json
{
    "status": false,
    "message": "Error description",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

---

## ⚠️ Error Handling

### HTTP Status Codes
| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Internal Server Error |

### Common Error Messages
- `User tidak ditemukan` - User not found
- `Data tidak terdeteksi` - Data not detected
- `Validasi gagal` - Validation failed
- `Format gambar tidak valid` - Invalid image format

---

## 👥 User Management APIs

### 1. Get Users with Pagination

**Endpoint**: `GET /users`

**Description**: Retrieve a paginated list of users with search and filter options.

**Query Parameters**:
| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| page | integer | No | 1 | Page number |
| per_page | integer | No | 15 | Items per page (max: 100) |
| search | string | No | - | Search by name or email |
| role | string | No | - | Filter by role name |
| status | string | No | - | Filter by status (active/inactive) |

**Example Request**:
```http
GET /api/users?page=1&per_page=20&search=john&role=Customer&status=active
```

**Success Response**:
```json
{
    "status": true,
    "message": "Data users berhasil diambil",
    "data": [
        {
            "id": 1,
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

### 2. Get User Detail

**Endpoint**: `GET /user/{id}/detail`

**Description**: Get comprehensive user information including statistics and recent activities.

**Path Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | User ID |

**Example Request**:
```http
GET /api/user/123/detail
```

**Success Response**:
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

**Error Response**:
```json
{
    "status": false,
    "message": "User tidak ditemukan"
}
```

---

## 📸 Face Management APIs

### 3. Get User Selfie

**Endpoint**: `GET /user-selfie/{id}`

**Description**: Retrieve selfie image data for a specific user.

**Path Parameters**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | User ID |

**Example Request**:
```http
GET /api/user-selfie/123
```

**Success Response**:
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

---

### 4. Get All User Selfies

**Endpoint**: `GET /user-selfies`

**Description**: Retrieve selfie image data for all users.

**Example Request**:
```http
GET /api/user-selfies
```

**Success Response**:
```json
{
    "status": true,
    "data": [
        {
            "user_id": 123,
            "user_name": "John Doe",
            "selfie": "data:image/jpeg;base64,/9j/4AAQ...",
            "img_base64": null
        },
        {
            "user_id": 124,
            "user_name": "Jane Smith",
            "selfie": null,
            "img_base64": "data:image/jpeg;base64,/9j/4AAQ..."
        }
    ]
}
```

---

### 5. Register Face

**Endpoint**: `POST /register-face`

**Description**: Register or update user's face image for validation.

**Request Body**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | Yes | User ID |
| face_image | string | Yes | Base64 encoded face image |
| image_type | string | No | Type of image field (`selfie` or `img_base64`) |

**Example Request**:
```json
{
    "user_id": 123,
    "face_image": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...",
    "image_type": "img_base64"
}
```

**Success Response**:
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

**Validation Rules**:
- `user_id`: Required, must exist in users table
- `face_image`: Required string with valid base64 image format
- `image_type`: Optional, must be either "selfie" or "img_base64"

---

### 6. Validate Face

**Endpoint**: `POST /validate-face`

**Description**: Validate user face against stored reference image.

**Request Body**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| user_id | integer | Yes | User ID |
| face_image | string | Yes | Base64 encoded face image |

**Example Request**:
```json
{
    "user_id": 123,
    "face_image": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD..."
}
```

**Success Response**:
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

**Failed Validation Response**:
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

---

## 📦 Package & Booking APIs

### 7. Check User Package

**Endpoint**: `POST /check-paket`

**Description**: Check if user has any active packages (without specific class validation).

**Request Body**:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id_user | integer | Yes | User ID |

**Example Request**:
```json
{
    "id_user": 123
}
```

**Success Response**:
```json
{
    "status": true,
    "message": "User memiliki paket aktif",
    "data": {
        "user_id": 123,
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
                "created_at": "2025-08-01T10:00:00Z"
            },
            {
                "order_paket_id": 790,
                "paket_id": 102,
                "paket_name": "Fitness Package",
                "paket_type": "Weekly",
                "paket_durasi": 7,
                "expired_date": "2025-09-15",
                "status": "Active",
                "remaining_days": 16,
                "created_at": "2025-08-15T10:00:00Z"
            }
        ]
    }
}
```

**No Active Package Response**:
```json
{
    "status": false,
    "message": "User tidak memiliki paket aktif"
}
```
```

---

## 📥 Import & Utility APIs

### 8. Import Users

**Endpoint**: `POST /import-user`

**Description**: Import users from Excel file.

**Request Body** (multipart/form-data):
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| data | file | Yes | Excel file containing user data |

**Example Request**:
```http
POST /api/import-user
Content-Type: multipart/form-data

data: [Excel file]
```

**Success Response**:
```json
{
    "status": true,
    "message": "Berhasil import data"
}
```

---

### 9. Import User Packages

**Endpoint**: `POST /import-user-paket`

**Description**: Import user package orders from Excel file.

**Request Body** (multipart/form-data):
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| data | file | Yes | Excel file containing package data |

**Success Response**:
```json
{
    "status": true,
    "message": "Berhasil import data"
}
```

---

### 10. Import Class Bookings

**Endpoint**: `POST /import-booking-kelas`

**Description**: Import class bookings from Excel file.

**Request Body** (multipart/form-data):
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| data | file | Yes | Excel file containing booking data |

**Success Response**:
```json
{
    "status": true,
    "message": "Berhasil import data"
}
```

---

### 11. Send Test Email

**Endpoint**: `GET /send-email`

**Description**: Send test confirmation email.

**Example Request**:
```http
GET /api/send-email
```

**Success Response**:
```json
{
    "status": true,
    "message": "Berhasil"
}
```

---

## ⚡ Rate Limiting

API endpoints may be subject to rate limiting:

- **Default Limit**: 60 requests per minute per IP
- **Authenticated**: 100 requests per minute per user
- **File Upload**: 10 requests per minute per IP

**Rate Limit Headers**:
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1693382400
```

---

## 💡 Examples

### cURL Examples

#### Get Users
```bash
curl -X GET "http://localhost:8000/api/users?page=1&per_page=10" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json"
```

#### Register Face
```bash
curl -X POST "http://localhost:8000/api/register-face" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 123,
    "face_image": "data:image/jpeg;base64,/9j/4AAQ...",
    "image_type": "img_base64"
  }'
```

#### Import Users
```bash
curl -X POST "http://localhost:8000/api/import-user" \
  -H "Accept: application/json" \
  -F "data=@users.xlsx"
```

### JavaScript Examples

#### Get User Detail
```javascript
fetch('http://localhost:8000/api/user/123/detail', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

#### Validate Face
```javascript
fetch('http://localhost:8000/api/validate-face', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    user_id: 123,
    face_image: 'data:image/jpeg;base64,/9j/4AAQ...'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Python Examples

#### Get Users with Search
```python
import requests

url = "http://localhost:8000/api/users"
params = {
    "page": 1,
    "per_page": 20,
    "search": "john",
    "role": "Customer"
}
headers = {
    "Accept": "application/json",
    "Content-Type": "application/json"
}

response = requests.get(url, params=params, headers=headers)
data = response.json()
print(data)
```

---

## 🛠️ Development & Testing

### Testing Endpoints

1. **Postman Collection**: Import the provided Postman collection
2. **Test Scripts**: Use the provided PHP test scripts
3. **Browser Testing**: Access GET endpoints directly in browser

### Environment Setup

```bash
# Start Laravel server
php artisan serve --port=8000

# Clear cache (if needed)
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Common Issues

1. **CORS Errors**: Configure CORS settings in `config/cors.php`
2. **File Upload Limits**: Check `php.ini` settings for `upload_max_filesize`
3. **Memory Limits**: Adjust PHP memory limits for large imports
4. **Rate Limiting**: Implement appropriate rate limiting for production

---

## 📞 Support

For API support and questions:

- **Documentation**: This file
- **Test Scripts**: Available in project root
- **Error Logs**: Check Laravel logs in `storage/logs/`
- **Database**: Ensure proper database connections

---

**Last Updated**: August 29, 2025  
**Version**: 1.0.0  
**Maintained by**: Limitless Fitness Development Team
