<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\PaginatedResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
  /**
   * 📋 TODO: Implementar listagem de livros
   *
   * Funcionalidades esperadas:
   * - Paginação (15 itens por página por padrão)
   * - Filtros múltiplos:
   *   - ?q=termo (busca em título e gênero)
   *   - ?author_id=1 (filtrar por autor)
   *   - ?disponivel=true (filtrar por disponibilidade)
   *   - ?ano_de=1800&ano_ate=1900 (filtrar por faixa de anos)
   * - Ordenação (?sort=titulo)
   * - Eager loading do autor quando necessário
   *
   * Resposta esperada: PaginatedResource com BookResource
   * Status: 200 OK
   */
  public function index(Request $request)
  {
    // TODO: Implementar aqui
    //
    // Dicas:
    // - Use os scopes do Model Book: search(), byAuthor(), byAvailability(), byYearRange()
    // - Use with('author') para eager loading
    // - Use when() para aplicar filtros condicionalmente
    // - Normalize per_page (min: 1, max: 100, default: 15)
    //
    // Exemplo:
    // $books = Book::with('author')
    //             ->when($request->q, function($query, $term) {
    //                 $query->search($term);
    //             })
    //             ->when($request->author_id, function($query, $authorId) {
    //                 $query->byAuthor($authorId);
    //             })
    //             ->when($request->has('disponivel'), function($query) use ($request) {
    //                 $query->byAvailability($request->boolean('disponivel'));
    //             })
    //             ->when($request->ano_de || $request->ano_ate, function($query) use ($request) {
    //                 $query->byYearRange($request->ano_de, $request->ano_ate);
    //             })
    //             ->orderBy($request->sort ?? 'titulo')
    //             ->paginate($request->per_page ?? 15);
    //
    // return new PaginatedResource(BookResource::collection($books));

    $validated = $request->validate([
      'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
      'q' => ['sometimes', 'string'],
      'sort' => ['sometimes', 'string'],
      'direction' => ['sometimes', 'string'],
      'author_id' => ['sometimes', 'integer'],
      'disponivel' => ['sometimes', 'boolean'],
      'ano_de' => ['sometimes', 'integer'],
      'ano_ate' => ['sometimes', 'integer'],
    ]);


    $query = Book::with('author');

    if(isset($validated['q'])) {
      $query->search($validated['q']);
    }

    if(isset($validated['author_id'])) {
      $query->byAuthor($validated['author_id']);
    }

    if(isset($validated['disponivel'])) {
      $query->byAvailability($validated['disponivel']);
    }

    if(isset($validated['ano_de']) || isset($validated['ano_ate'])) {
      $query->byYearRange($validated['ano_de'] ?? null, $validated['ano_ate'] ?? null);
    }

    $query->orderByField($validated['sort'] ?? 'titulo', $validated['direction'] ?? 'asc');

    $books = $query->paginate($validated['per_page'] ?? 15);
    return response()->json($books);
  }

  /**
   * 📋 TODO: Implementar criação de livro
   *
   * Funcionalidades esperadas:
   * - Validação automática via StoreBookRequest
   * - Criação do livro no banco
   * - Resposta formatada com BookResource
   * - Tratamento automático de título duplicado (409 via FormRequest)
   *
   * Status: 201 Created
   * Status: 409 Conflict (título duplicado para mesmo autor)
   * Status: 422 Unprocessable Entity (validação)
   */
  public function store(StoreBookRequest $request)
  {
    // TODO: Implementar aqui
    //
    // Dicas:
    // - Os dados já estão validados pelo StoreBookRequest
    // - A validação de título duplicado é automática (retorna 409)
    // - Use Book::create() para criar
    // - Use BookResource para formatar resposta
    // - Retorne status 201
    // - Considere carregar o autor com with('author') se necessário
    //
    // Exemplo:
    // $book = Book::create($request->validated());
    // $book->load('author'); // Carregar dados do autor
    // return response()->json([
    //     'data' => new BookResource($book)
    // ], 201);

    $book = Book::create($request->validated());
    $book->load('author'); // Carregar dados do autor
    return response()->json([
      'data' => new BookResource($book)
    ], 201);
  }

  /**
   * 📋 TODO: Implementar busca de livro por ID
   *
   * Funcionalidades esperadas:
   * - Buscar livro por ID
   * - Carregar dados do autor (eager loading)
   * - Retornar 404 se não encontrado
   * - Resposta formatada com BookResource
   *
   * Status: 200 OK
   * Status: 404 Not Found
   */
  public function show($id)
  {
    // TODO: Implementar aqui
    //
    // Dicas:
    // - Use Book::with('author')->findOrFail() para busca com autor
    // - Use BookResource para formatar resposta
    //
    // Exemplo:
    // $book = Book::with('author')->findOrFail($id);
    // return response()->json([
    //     'data' => new BookResource($book)
    // ]);

    $book = Book::with('author')->findOrFail($id);
    return response()->json([
      'data' => new BookResource($book)
    ]);
  }

  /**
   * 📋 TODO: Implementar atualização de livro
   *
   * Funcionalidades esperadas:
   * - Validação automática via UpdateBookRequest
   * - Atualização do livro no banco
   * - Retornar 404 se não encontrado
   * - Resposta formatada com BookResource
   * - Tratamento de título duplicado (409 via FormRequest)
   *
   * Status: 200 OK
   * Status: 404 Not Found
   * Status: 409 Conflict (título duplicado)
   * Status: 422 Unprocessable Entity (validação)
   */
  public function update(UpdateBookRequest $request, $id)
  {
    // TODO: Implementar aqui
    //
    // Dicas:
    // - Use Book::findOrFail() para busca com 404 automático
    // - Use $book->update() para atualizar
    // - Use BookResource para formatar resposta
    // - Carregue o autor se necessário
    //
    // Exemplo:
    // $book = Book::findOrFail($id);
    // $book->update($request->validated());
    // $book->load('author'); // Recarregar com dados do autor
    // return response()->json([
    //     'data' => new BookResource($book)
    // ]);

    $book = Book::findOrFail($id);
    $books = Book::all();

    if (count($books->where('titulo', $request->titulo)) > 1) {
      return response()->json([
        'errors' => [
          'titulo' => 'Título duplicado'
        ]
      ], 409);
    }

    $book->update($request->validated());
    $book->load('author'); // Recarregar com dados do autor
    return response()->json([
      'data' => new BookResource($book)
    ]);
  }

  /**
   * 📋 TODO: Implementar exclusão de livro
   *
   * Funcionalidades esperadas:
   * - Buscar livro por ID
   * - Retornar 404 se não encontrado
   * - Excluir livro
   * - Resposta sem conteúdo (204)
   *
   * Status: 204 No Content (sucesso)
   * Status: 404 Not Found (livro não existe)
   */
  public function destroy($id)
  {
    // TODO: Implementar aqui
    //
    // Dicas:
    // - Use Book::findOrFail() para busca
    // - Use $book->delete() para excluir
    // - Retorne response()->noContent() para 204
    //
    // Exemplo:
    // $book = Book::findOrFail($id);
    // $book->delete();
    // return response()->noContent(); // 204

    $book = Book::findOrFail($id);
    $book->delete();
    return response()->noContent();
  }
}
