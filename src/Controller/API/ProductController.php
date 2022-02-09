<?php

namespace App\Controller\API;

use App\Entity\Resource\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api")]
class ProductController extends AbstractController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Rest\Get(
     *     path="/products",
     *     name="api_product_list"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="1"
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1"
     * )
     * @Rest\View(serializerGroups={ "Default", "items"="listProduct" })
     */
    public function listProducts(ParamFetcherInterface $paramFetcher): View
    {
        $products = $this->productRepository
            ->paginatedProducts($paramFetcher->get('limit'), $paramFetcher->get('page'));

        return View::create()
            ->setStatusCode(200)
            ->setFormat("json")
            ->setData(["status" => "ok", "status_code" => "200", "data" => $products]);
    }

    /**
     * @Rest\Get(
     *     path="/products/{id}",
     *     name="api_product_show",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View(serializerGroups={ "Default", "items"="showProduct" })
     */
    public function showProduct(Product $product): View
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat("json")
            ->setData(["status" => "ok", "status_code" => "200", "data" => $product]);
    }
}
