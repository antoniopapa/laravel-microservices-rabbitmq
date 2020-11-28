<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Jobs\ProductCreated;
use App\Jobs\ProductDeleted;
use App\Jobs\ProductUpdated;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Microservices\UserService;

class ProductController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $this->userService->allows('view', 'products');

        $products = Product::paginate();

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $this->userService->allows('view', 'products');

        return new ProductResource(Product::find($id));
    }

    public function store(ProductCreateRequest $request)
    {
        $this->userService->allows('edit', 'products');

        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        ProductCreated::dispatch($product->toArray())->onQueue('checkout_queue');
        ProductCreated::dispatch($product->toArray())->onQueue('influencer_queue');

        return response($product, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $this->userService->allows('edit', 'products');

        $product = Product::find($id);

        $product->update($request->only('title', 'description', 'image', 'price'));

        ProductUpdated::dispatch($product->toArray())->onQueue('checkout_queue');
        ProductUpdated::dispatch($product->toArray())->onQueue('influencer_queue');

        return response($product, Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $this->userService->allows('edit', 'products');

        Product::destroy($id);

        ProductDeleted::dispatch($id)->onQueue('checkout_queue');
        ProductDeleted::dispatch($id)->onQueue('influencer_queue');

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
