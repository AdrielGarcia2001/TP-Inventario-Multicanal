<?php

namespace Tests\Feature;

use App\Events\ProductoActualizado;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ProductoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_rest_crud_validates_inputs_and_broadcasts_changes(): void
    {
        Event::fake();

        $this->getJson('/api/productos')->assertOk()->assertExactJson([]);

        $producto = $this->postJson('/api/productos', [
            'nombre' => 'Teclado',
            'precio' => 25000,
            'stock' => 12,
        ])->assertCreated()
            ->assertJsonPath('nombre', 'Teclado')
            ->assertJsonPath('stock', 12)
            ->json();

        $this->getJson('/api/productos/'.$producto['id'])
            ->assertOk()
            ->assertJsonPath('id', $producto['id']);

        $this->patchJson('/api/productos/'.$producto['id'], ['stock' => -1])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('stock');

        $this->patchJson('/api/productos/'.$producto['id'], ['stock' => 13])
            ->assertOk()
            ->assertJsonPath('stock', 13);

        $this->deleteJson('/api/productos/'.$producto['id'])->assertNoContent();
        $this->getJson('/api/productos/'.$producto['id'])->assertNotFound();

        Event::assertDispatched(ProductoActualizado::class, fn (ProductoActualizado $evento) => $evento->accion === 'creado');
        Event::assertDispatched(ProductoActualizado::class, fn (ProductoActualizado $evento) => $evento->accion === 'actualizado');
    }

    public function test_product_creation_requires_valid_fields(): void
    {
        $this->postJson('/api/productos', [
            'nombre' => '',
            'precio' => -1,
            'stock' => -1,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['nombre', 'precio', 'stock']);
    }

    public function test_graphql_uses_the_shared_product_service(): void
    {
        $this->postJson('/graphql', [
            'query' => 'mutation { crearProducto(nombre: "Mouse", precio: 18000, stock: 20) { id nombre precio stock } }',
        ])->assertOk()
            ->assertJsonPath('data.crearProducto.nombre', 'Mouse')
            ->assertJsonPath('data.crearProducto.stock', 20);

        $producto = Producto::firstOrFail();

        $this->postJson('/graphql', [
            'query' => sprintf('mutation { actualizarStock(id: %d, stock: 8) { id stock } }', $producto->id),
        ])->assertOk()
            ->assertJsonPath('data.actualizarStock.stock', 8);

        $this->postJson('/graphql', [
            'query' => '{ productos { id nombre stock } }',
        ])->assertOk()
            ->assertJsonPath('data.productos.0.nombre', 'Mouse')
            ->assertJsonPath('data.productos.0.stock', 8);
    }

    public function test_internal_product_endpoint_requires_the_shared_key(): void
    {
        $producto = Producto::create([
            'nombre' => 'Monitor',
            'precio' => 12000,
            'stock' => 3,
        ]);

        config(['services.grpc_internal_key' => 'test-internal-key']);

        $this->getJson('/api/interno/productos/'.$producto->id)
            ->assertForbidden();

        $this->withHeader('X-Internal-Key', 'test-internal-key')
            ->getJson('/api/interno/productos/'.$producto->id)
            ->assertOk()
            ->assertJsonPath('nombre', 'Monitor');
    }
}
