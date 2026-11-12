# FatherShops Backend

## About the Project

This is the backend part of the FatherShops technical task, developed using Laravel and PHP.

It connects with the external FatherShops API to get category, product and product details data and provides it to the Next.js frontend.

## Features

- Fetch all product categories.
- Fetch products based on the selected category.
- Fetch complete details of a selected product.
- Handle API errors and unavailable data.
- Use caching to reduce repeated API requests.
- Remove duplicate products.

## API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/categories` | Get all categories |
| GET | `/api/products/{id}` | Get products by category ID |
| GET | `/api/products/details/{id}` | Get product details |

## External API URLs

**Categories:**
`https://fatherstock-cache.b-cdn.net/category/hot-category.json`

**Products:**
`https://fatherstock-cache.b-cdn.net/category/{category_id}.json`

**Product Details:**
`https://fatherstock-cache.b-cdn.net/cache/{product_id}-f1.json`

## Sportswear Category Issue

During testing, the Sportswear category (ID: 59) was not loading its products correctly.

API URL:
`https://fatherstock-cache.b-cdn.net/category/59.json`

The API returns HTTP 200, but the response contains invalid JSON. Because of this, Laravel cannot properly read the product data.

This appears to be an issue with the external API response. The backend handles this error instead of displaying incorrect product data.

## How to Run the Project

**1. Install dependencies**

```bash
composer install
```

**2. Create the environment file**

```bash
copy .env.example .env
```

**3. Generate the application key**

```bash
php artisan key:generate
```

**4. Start the Laravel server**

```bash
php artisan serve
```

Backend URL: `http://127.0.0.1:8000`

## Frontend Connection

The Next.js frontend connects to the Laravel backend using the API base URL:

```env
NEXT_PUBLIC_LARAVEL_API_URL=http://127.0.0.1:8000/api
```

## Author

Zaid Nagori
