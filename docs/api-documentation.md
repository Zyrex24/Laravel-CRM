# Laravel CRM API Documentation

## Overview

The Laravel CRM API provides comprehensive endpoints for managing contacts, leads, activities, emails, users, and dashboard analytics. All endpoints are protected by Sanctum authentication and return consistent JSON responses.

## Authentication

All API endpoints require authentication using Laravel Sanctum. Include the bearer token in the Authorization header:

```
Authorization: Bearer {your-token}
```

## Base URL

```
/api
```

## Response Format

All API responses follow a consistent format:

```json
{
  "success": true|false,
  "message": "Response message",
  "data": {...},
  "meta": {...}
}
```

## Contacts API

### List Contacts
- **GET** `/api/contacts`
- **Parameters:**
  - `query` (optional): Search query
  - `type` (optional): `person`, `organization`, or `all`
  - `per_page` (optional): Results per page (default: 15)

### Create Contact
- **POST** `/api/contacts`
- **Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "type": "person|organization",
  "company": "Tech Corp",
  "industry": "Technology"
}
```

### Get Contact
- **GET** `/api/contacts/{id}`

### Update Contact
- **PUT** `/api/contacts/{id}`
- **Body:** Same as create contact

### Delete Contact
- **DELETE** `/api/contacts/{id}`

### Contact Statistics
- **GET** `/api/contacts/stats/overview`

### Search Contacts
- **POST** `/api/contacts/search`
- **Body:**
```json
{
  "query": "search term",
  "type": "person|organization",
  "company": "company name",
  "industry": "industry name"
}
```

## Leads API

### List Leads
- **GET** `/api/leads`
- **Parameters:**
  - `status` (optional): Lead status filter
  - `source` (optional): Lead source filter
  - `per_page` (optional): Results per page

### Create Lead
- **POST** `/api/leads`
- **Body:**
```json
{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "phone": "+1555123456",
  "company": "Potential Corp",
  "status": "new|contacted|qualified|proposal|negotiation|closed_won|closed_lost",
  "source": "website|social_media|referral|cold_call|email_campaign|trade_show|other",
  "value": 50000,
  "probability": 25,
  "expected_close_date": "2024-02-15"
}
```

### Get Lead
- **GET** `/api/leads/{id}`

### Update Lead
- **PUT** `/api/leads/{id}`

### Delete Lead
- **DELETE** `/api/leads/{id}`

### Convert Lead to Contact
- **POST** `/api/leads/{id}/convert`

### Pipeline Analytics
- **GET** `/api/leads/analytics/pipeline`

### Source Analytics
- **GET** `/api/leads/analytics/sources`

## Activities API

### List Activities
- **GET** `/api/activities`
- **Parameters:**
  - `type` (optional): Activity type filter
  - `status` (optional): Activity status filter
  - `contact_id` (optional): Filter by contact

### Create Activity
- **POST** `/api/activities`
- **Body:**
```json
{
  "type": "call|email|meeting|task|note",
  "subject": "Follow-up call",
  "description": "Discuss project requirements",
  "priority": "low|medium|high",
  "contact_id": 1,
  "due_date": "2024-02-15",
  "status": "pending|scheduled|in_progress|completed|cancelled"
}
```

### Get Activity
- **GET** `/api/activities/{id}`

### Update Activity
- **PUT** `/api/activities/{id}`

### Delete Activity
- **DELETE** `/api/activities/{id}`

### Complete Activity
- **POST** `/api/activities/{id}/complete`

### Activity Statistics
- **GET** `/api/activities/stats/overview`

### Upcoming Activities
- **GET** `/api/activities/upcoming`
- **Parameters:**
  - `days` (optional): Number of days to look ahead (default: 7)

### Overdue Activities
- **GET** `/api/activities/overdue`

## Email API

### List Emails
- **GET** `/api/emails`
- **Parameters:**
  - `type` (optional): `inbox`, `sent`, `draft`
  - `contact_id` (optional): Filter by contact

### Send Email
- **POST** `/api/emails/send`
- **Body:**
```json
{
  "to": "recipient@example.com",
  "subject": "Email subject",
  "body": "Email content",
  "cc": ["cc@example.com"],
  "bcc": ["bcc@example.com"],
  "contact_id": 1,
  "template_id": 1
}
```

### Get Email
- **GET** `/api/emails/{id}`

### Reply to Email
- **POST** `/api/emails/{id}/reply`
- **Body:**
```json
{
  "body": "Reply content",
  "cc": ["cc@example.com"],
  "bcc": ["bcc@example.com"]
}
```

### Forward Email
- **POST** `/api/emails/{id}/forward`
- **Body:**
```json
{
  "to": "forward@example.com",
  "body": "Forward message",
  "cc": ["cc@example.com"]
}
```

### Email Templates
- **GET** `/api/emails/templates`

### Email Statistics
- **GET** `/api/emails/stats/overview`

### Email Thread
- **GET** `/api/emails/thread/{threadId}`

### Mark as Read/Unread
- **PATCH** `/api/emails/{id}/read`
- **Body:**
```json
{
  "read": true|false
}
```

## Users API

### List Users
- **GET** `/api/users`
- **Parameters:**
  - `role` (optional): Filter by role
  - `status` (optional): Filter by status

### Create User
- **POST** `/api/users`
- **Body:**
```json
{
  "name": "New User",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "admin|manager|user",
  "status": "active|inactive|suspended"
}
```

### Get User
- **GET** `/api/users/{id}`

### Update User
- **PUT** `/api/users/{id}`

### Delete User
- **DELETE** `/api/users/{id}`

### Upload Avatar
- **POST** `/api/users/{id}/avatar`
- **Body:** Form data with `avatar` file

### Update Permissions
- **PATCH** `/api/users/{id}/permissions`
- **Body:**
```json
{
  "permissions": ["contacts.view", "contacts.create", "leads.view"]
}
```

### Update Preferences
- **PATCH** `/api/users/{id}/preferences`
- **Body:**
```json
{
  "timezone": "UTC",
  "language": "en",
  "notifications_email": true,
  "notifications_browser": true,
  "theme": "light|dark|auto"
}
```

### Roles and Permissions
- **GET** `/api/users/roles-permissions`

### User Activity Log
- **GET** `/api/users/{id}/activity-log`

## Dashboard API

### Overview
- **GET** `/api/dashboard/overview`

### Pipeline Data
- **GET** `/api/dashboard/pipeline`

### Revenue Analytics
- **GET** `/api/dashboard/revenue`
- **Parameters:**
  - `period` (optional): `daily`, `weekly`, `monthly`, `yearly`

### Team Performance
- **GET** `/api/dashboard/team-performance`

### Upcoming Tasks
- **GET** `/api/dashboard/upcoming-tasks`

### Notifications
- **GET** `/api/dashboard/notifications`

### Quick Actions
- **GET** `/api/dashboard/quick-actions`

### System Health
- **GET** `/api/dashboard/system-health`

## Error Handling

The API returns appropriate HTTP status codes:

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

Validation errors return detailed error messages:

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "name": ["The name field is required."]
  }
}
```

## Rate Limiting

API endpoints are rate limited to prevent abuse. Default limits:
- 60 requests per minute for authenticated users
- 10 requests per minute for unauthenticated requests

## Pagination

List endpoints support pagination with the following parameters:
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 15, max: 100)

Pagination metadata is included in the response:

```json
{
  "meta": {
    "total": 150,
    "per_page": 15,
    "current_page": 1,
    "last_page": 10,
    "from": 1,
    "to": 15
  }
}
```
