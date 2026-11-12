<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\ConnectionException;

class FatherShopsController extends Controller
{
    public function categories()
    {
        try {
            $data = Cache::remember('categories', 600, function () {
                $response = Http::timeout(15)->get(
                    'https://fatherstock-cache.b-cdn.net/category/hot-category.json'
                );

                if ($response->status() === 404) {
                    abort(404, 'Categories not found');
                }

                if (!$response->successful()) {
                    abort(502, 'Unable to fetch categories');
                }

                return $response->json();
            });

            if (empty($data)) {
                return response()->json(['message' => 'Categories not found'], 404);
            }

            return response()->json($data);

        } catch (ConnectionException $e) {
            return response()->json(['message' => 'Category server unavailable'], 502);
        }
    }

    public function products($id)
    {
        try {
            $data = Cache::remember('products_' . $id, 600, function () use ($id) {
                $response = Http::timeout(15)->get(
                    "https://fatherstock-cache.b-cdn.net/category/{$id}.json"
                );

                if ($response->status() === 404) {
                    abort(404, 'Products not found');
                }

                if (!$response->successful()) {
                    abort(502, 'Unable to fetch products');
                }

                return $response->json();
            });

            if (empty($data)) {
                return response()->json(['message' => 'Products not found'], 404);
            }

            if (isset($data['data']['products']) && is_array($data['data']['products'])) {
                $uniqueProducts = [];
                $seenIds = [];

                foreach ($data['data']['products'] as $product) {
                    if (!isset($product['product_id'])) {
                        $uniqueProducts[] = $product;
                        continue;
                    }

                    $productId = (string) $product['product_id'];

                    if (!isset($seenIds[$productId])) {
                        $seenIds[$productId] = true;
                        $uniqueProducts[] = $product;
                    }
                }

                $data['data']['products'] = array_values($uniqueProducts);

                Cache::put('products_' . $id, $data, 600);
            }

            return response()->json($data);

        } catch (ConnectionException $e) {
            return response()->json(['message' => 'Product server unavailable'], 502);
        }
    }

    public function productDetails($id)
    {
        try {
            $data = Cache::remember('product_details_' . $id, 600, function () use ($id) {
                $response = Http::timeout(15)->get(
                    "https://fatherstock-cache.b-cdn.net/cache/{$id}-f1.json"
                );

                if ($response->status() === 404) {
                    abort(404, 'Product details not found');
                }

                if (!$response->successful()) {
                    abort(502, 'Unable to fetch product details');
                }

                return $response->json();
            });

            if (empty($data)) {
                return response()->json(['message' => 'Product details not found'], 404);
            }

            return response()->json($data);

        } catch (ConnectionException $e) {
            return response()->json(['message' => 'Product details server unavailable'], 502);
        }
    }
}
