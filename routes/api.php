<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// API Routes
Route::get('authors/{author}/books', [AuthorController::class, 'books'])
  ->name('authors.books');

Route::apiResource('authors', AuthorController::class);
Route::apiResource('books', BookController::class);


// Generate a simple numeric token
function generateToken(): string
{
  $token = '';
  for ($i = 0; $i < 5; $i++) {
    $token .= random_int(0, 9);
  }
  return $token;
}

function registrarToken($userId, $name, $abilities, $ttlMinutes)
{
  $token = generateToken();
  $now = Carbon::now();
  $exp = $now->copy()->addMinutes($ttlMinutes);

  $tokenDb = [
    'tokenable_type' => User::class,
    'tokenable_id' => $userId,
    'name' => $name,
    'token' => $token,
    'abilities' => json_encode($abilities),
    'expires_at' => $exp,
    'created_at' => $now,
    'updated_at' => $now
  ];

  DB::table('personal_access_tokens')->insert($tokenDb);
  return ['token' => $token, 'expires_at' => $exp];
}

// Check a bearer token with optional ability check
function verifyToken(Request $request, ?string $ability = null)
{
  $token = $request->bearerToken();
  if (!$token) return null;

  $row = DB::table('personal_access_tokens')->where('token', $token)->first();
  if (!$row) return null;

  if ($row->expires_at && Carbon::parse($row->expires_at)->isPast()) {
    DB::table('personal_access_tokens')->where('id', $row->id)->delete();
    return null;
  }

  $abilities = json_decode($row->abilities ?? '[]', true);
  if ($ability && !in_array($ability, $abilities) && !in_array('*', $abilities)) {
    return null;
  }

  return $row; // return the token row (or user info if desired)
}

Route::post('/signin', function (Request $req) {
  $data = $req->validate([
    'email' => ['required', 'email'],
    'password' => ['required', 'string']
  ]);

  $user = User::where('email', $data['email'])->where('password', $data['password'])->first();
  if (!$user) {
    return response()->json(['message' => 'Credenciais Invalidas'], 401);
  }

  $access = registrarToken($user->id, 'access', ['*'], 5);
  $refresh = registrarToken($user->id, 'refresh', ['refresh'], 10);

  return response()->json([
    'access_token' => $access['token'],
    'refresh_token' => $access['token'],
    'user' => $user
  ]);
});


Route::get("/profile", function(Request $req){
$token = verifyToken($req);

if(!$token){
return response()->json([
  'message'=>'Nao autorizado'
], 401);
}

$user = User::find($token->tokenable_id);
return Response()->json(['mesagem'=>'acesso liberado', 'user'=> $user]);
});
