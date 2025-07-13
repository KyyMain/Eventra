# Eventra - Event Management System

## Overview
Eventra is a comprehensive event management system built with CodeIgniter 4, featuring user authentication, event creation and management, registration system, and administrative controls.

## Features

### Core Features
- **User Management**: Registration, authentication, profile management
- **Event Management**: Create, edit, delete, and manage events
- **Registration System**: Users can register for events with capacity limits
- **Admin Dashboard**: Administrative controls and statistics
- **Security**: CSRF protection, rate limiting, input validation
- **Caching**: Redis-based caching for improved performance
- **API**: RESTful API endpoints for mobile/external integration

### Security Features
- **Rate Limiting**: Different limits for API, authentication, and general requests
- **CSRF Protection**: Custom CSRF filter with enhanced security
- **Input Validation**: Custom validation rules for strong passwords, unique fields
- **Security Headers**: Comprehensive security headers including CSP, HSTS
- **SQL Injection Protection**: Parameterized queries and input sanitization

## Installation

### Requirements
- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Redis (optional, for caching)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Eventra1
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   # Edit .env with your database and other configurations
   ```

4. **Database Setup**
   ```bash
   php spark migrate
   php spark db:seed DatabaseSeeder
   ```

5. **Set Permissions**
   ```bash
   chmod -R 755 writable/
   ```

## Configuration

### Environment Variables
Key environment variables to configure:

```env
# Database
database.default.hostname = localhost
database.default.database = eventra_db
database.default.username = your_username
database.default.password = your_password

# Cache (Redis)
cache.handler = redis
cache.redis.host = 127.0.0.1
cache.redis.port = 6379

# Email
email.protocol = smtp
email.SMTPHost = your-smtp-host
email.SMTPUser = your-smtp-user
email.SMTPPass = your-smtp-password
```

### Security Configuration
- CSRF protection is enabled by default
- Rate limiting is configured for different endpoint types
- Security headers are automatically applied
- Strong password validation is enforced

## API Documentation

### Authentication Endpoints
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout
- `POST /api/auth/refresh` - Refresh token

### Event Endpoints
- `GET /api/events` - List events (with pagination)
- `GET /api/events/{id}` - Get event details
- `POST /api/events` - Create event (authenticated)
- `PUT /api/events/{id}` - Update event (authenticated)
- `DELETE /api/events/{id}` - Delete event (authenticated)

### Registration Endpoints
- `POST /api/events/{id}/register` - Register for event
- `DELETE /api/events/{id}/register` - Cancel registration
- `GET /api/users/{id}/registrations` - Get user registrations

### Rate Limits
- **API Endpoints**: 100 requests per hour
- **Authentication**: 5 requests per 5 minutes
- **General**: 60 requests per minute

## Database Schema

### Users Table
- `id` (Primary Key)
- `username` (Unique)
- `email` (Unique)
- `password` (Hashed)
- `first_name`
- `last_name`
- `status` (active/inactive)
- `created_at`
- `updated_at`

### Events Table
- `id` (Primary Key)
- `user_id` (Foreign Key)
- `title`
- `description`
- `event_date`
- `location`
- `capacity`
- `status` (active/cancelled)
- `created_at`
- `updated_at`

### Event Registrations Table
- `id` (Primary Key)
- `event_id` (Foreign Key)
- `user_id` (Foreign Key)
- `status` (registered/cancelled)
- `registered_at`

## Validation Rules

### Custom Validation Rules
- `strong_password`: Requires uppercase, lowercase, number, and special character
- `unique_email`: Checks email uniqueness (excludes current user on update)
- `unique_username`: Checks username uniqueness (excludes current user on update)
- `future_date`: Ensures event dates are in the future
- `valid_capacity`: Validates event capacity range (1-10000)

## Caching Strategy

### Cache Keys
- `user_stats`: User statistics (TTL: 1 hour)
- `event_stats_{id}`: Event statistics (TTL: 30 minutes)
- `popular_events`: Popular events list (TTL: 1 hour)
- `rate_limit_{type}_{ip}`: Rate limiting counters

### Cache Invalidation
- User stats cache is invalidated on user creation/update
- Event stats cache is invalidated on registration changes
- Popular events cache is invalidated on new registrations

## Maintenance

### Log Cleanup
Use the built-in command to clean up old log files:
```bash
php spark logs:cleanup 30  # Clean logs older than 30 days
```

### Database Optimization
Run the performance migration to add indexes:
```bash
php spark migrate
```

### Cache Management
Clear cache when needed:
```bash
php spark cache:clear
```

## Security Best Practices

1. **Regular Updates**: Keep CodeIgniter and dependencies updated
2. **Environment Files**: Never commit `.env` files to version control
3. **Database Credentials**: Use strong, unique database passwords
4. **HTTPS**: Always use HTTPS in production
5. **Backup**: Regular database and file backups
6. **Monitoring**: Monitor logs for suspicious activity

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `.env`
   - Ensure database server is running
   - Verify database exists

2. **Permission Errors**
   - Check `writable/` directory permissions
   - Ensure web server can write to cache and logs

3. **Rate Limiting Issues**
   - Check Redis connection if using Redis cache
   - Verify cache configuration

4. **CSRF Token Errors**
   - Ensure CSRF tokens are included in forms
   - Check session configuration

## Development

### Code Structure
- `app/Controllers/` - Application controllers
- `app/Models/` - Database models
- `app/Services/` - Business logic services
- `app/Filters/` - Request/response filters
- `app/Validation/` - Custom validation rules

### Testing
Run tests with:
```bash
vendor/bin/phpunit
```

### Contributing
1. Fork the repository
2. Create a feature branch
3. Make changes with tests
4. Submit a pull request

## License
This project is licensed under the MIT License.

## Support
For support and questions, please create an issue in the repository or contact the development team.
