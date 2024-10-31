<?php


/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use App\Models\User;
use Tests\TestCase;


pest()->extend(Tests\TestCaseWithAuth::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature','Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
function expectedBookJsonStructure()
{
    return [
        'data' => [
            '*' => ['id', 'titulo', 'autor', 'data_criacao', 'data_atualizacao']
        ],
        'status',
        'message'
    ];
}


function expectedOneBookJsonStructure()
{
    return [
        'data' => ['id', 'titulo', 'autor', 'data_criacao', 'data_atualizacao']
    ];
}

function expectedLoanJsonStructure()
{
    return [
        'data' => [
            '*' => ['id', 'usuario', 'livro', 'data_emprestimo', 'data_devolucao','observacao']
        ],
        'status',
        'message'
    ];
}
function expectedOneLoanJsonStructure()
{
    return [
        'data' => ['id', 'usuario', 'livro', 'data_emprestimo', 'data_devolucao','observacao']
    ];

}








// json de errors
function expectedErrorJsonStructure()
{
    return [
        'message',
        'errors',
        'status',
        'data'
    ];
}



beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('Test Token')->plainTextToken; // Para Sanctum
});
