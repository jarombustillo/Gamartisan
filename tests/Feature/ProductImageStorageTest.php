<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Category;
use App\Models\Product;
use App\Support\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Product images on an S3 bucket (UPLOADS_DISK=s3), as used on Laravel Cloud.
 */
class ProductImageStorageTest extends TestCase
{
    use RefreshDatabase;

    private const BUCKET_URL = 'https://bucket.example.test';

    private Artist $artist;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        config(['filesystems.uploads_disk' => 's3']);
        Storage::fake('s3', ['url' => self::BUCKET_URL]);
        Storage::fake('uploads');

        $this->artist = Artist::create(['firstName' => 'Art', 'lastName' => 'Ist', 'email' => 'art@example.test', 'artPass' => 'x', 'status' => 'active']);
        $this->category = Category::create(['catName' => 'Paintings']);
    }

    private function png(string $clientName = 'photo.png'): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='));

        return new UploadedFile($path, $clientName, 'image/png', null, true);
    }

    private function asArtist(): static
    {
        return $this->withSession(['user_type' => 'artist', 'user_id' => $this->artist->artistID, 'user_name' => 'Art']);
    }

    private function productForm(array $extra = []): array
    {
        return $extra + [
            'prodName' => 'Pic', 'prodPrice' => 10, 'prodStock' => 1,
            'categoryID' => $this->category->categoryID, 'prodStatus' => 'available',
        ];
    }

    private function bucketPath(string $url): string
    {
        return substr($url, strlen(self::BUCKET_URL . '/'));
    }

    public function test_upload_goes_to_the_bucket_and_saves_its_url(): void
    {
        $this->asArtist()->post(route('artist.products.store'), $this->productForm(['prodImage' => $this->png('evil.html')]))
            ->assertRedirect(route('artist.products'));

        $url = Product::sole()->prodImage;
        $this->assertStringStartsWith(self::BUCKET_URL . '/products/', $url);
        $this->assertStringEndsWith('.png', $url); // detected type, not the client's ".html"
        Storage::disk('s3')->assertExists($this->bucketPath($url));
        Storage::disk('uploads')->assertDirectoryEmpty('/');
        $this->assertSame($url, Product::sole()->image_url);
    }

    public function test_replacing_an_image_deletes_the_old_object_from_the_bucket(): void
    {
        $this->asArtist()->post(route('artist.products.store'), $this->productForm(['prodImage' => $this->png()]));
        $product = Product::sole();
        $old = $product->prodImage;

        $this->asArtist()->put(route('artist.products.update', $product), $this->productForm(['prodImage' => $this->png()]))
            ->assertRedirect(route('artist.products'));

        $new = $product->fresh()->prodImage;
        $this->assertNotSame($old, $new);
        Storage::disk('s3')->assertMissing($this->bucketPath($old));
        Storage::disk('s3')->assertExists($this->bucketPath($new));
    }

    public function test_replacing_a_legacy_local_image_moves_it_to_the_bucket(): void
    {
        Storage::disk('uploads')->put('products/legacy.png', 'old');
        $product = Product::create([
            'artistID' => $this->artist->artistID, 'categoryID' => $this->category->categoryID,
            'prodName' => 'Old', 'prodPrice' => 10, 'prodStock' => 1, 'prodStatus' => 'available',
            'prodImage' => '/uploads/products/legacy.png',
        ]);

        $this->asArtist()->put(route('artist.products.update', $product), $this->productForm(['prodImage' => $this->png()]));

        Storage::disk('uploads')->assertMissing('products/legacy.png');
        $this->assertStringStartsWith(self::BUCKET_URL . '/products/', $product->fresh()->prodImage);
    }

    public function test_upload_does_not_send_a_per_file_visibility(): void
    {
        // Laravel Cloud buckets are Cloudflare R2, which rejects per-object ACLs
        // ("NotImplemented"). Visibility is set on the bucket, so store() must not pass one.
        $disk = \Mockery::mock(\Illuminate\Filesystem\FilesystemAdapter::class);
        $disk->shouldReceive('putFileAs')->once()
            ->withArgs(function ($dir, $file, $name, $options = []) {
                $visibility = is_array($options) ? ($options['visibility'] ?? null) : $options;

                return $dir === 'products' && $visibility === null;
            })
            ->andReturn('products/x.png');
        $disk->shouldReceive('url')->with('products/x.png')->andReturn(self::BUCKET_URL . '/products/x.png');
        Storage::set('s3', $disk);

        $this->assertSame(self::BUCKET_URL . '/products/x.png', ProductImage::store($this->png()));
    }

    public function test_legacy_local_images_still_render_while_using_the_bucket(): void
    {
        $this->assertSame(asset('uploads/products/a.png'), ProductImage::url('/uploads/products/a.png'));
    }

    public function test_deleting_a_url_outside_the_bucket_does_nothing(): void
    {
        Storage::disk('s3')->put('products/keep.png', 'x');

        ProductImage::delete('https://elsewhere.example/products/keep.png');

        Storage::disk('s3')->assertExists('products/keep.png');
    }
}
