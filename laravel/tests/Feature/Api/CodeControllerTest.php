<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CodeControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('codes')->whereIn('code_type', ['gender', 'color'])->delete();
        DB::table('codes')->insert([
            ['code_type' => 'gender', 'code' => 0, 'name' => '不明',   'sort_order' => 0],
            ['code_type' => 'gender', 'code' => 1, 'name' => '男性',   'sort_order' => 1],
            ['code_type' => 'gender', 'code' => 2, 'name' => '女性',   'sort_order' => 2],
            ['code_type' => 'color',  'code' => 1, 'name' => '赤',     'sort_order' => 1],
            ['code_type' => 'color',  'code' => 2, 'name' => '青',     'sort_order' => 2],
        ]);
    }

    /** 全件取得: 200 が返り、複数 code_type のデータが混在した連想配列が返る */
    public function test_index_returns_all_codes_as_map(): void
    {
        $response = $this->getJson('/codes');

        $response->assertStatus(200)
            ->assertJsonStructure(['0'])         // gender の code=0 (他 code_type と重複しない)
            ->assertJsonPath('0', '不明');        // gender/code=0 の name
    }

    /** パスパラメーターで絞り込める */
    public function test_index_filters_by_path_parameter(): void
    {
        $response = $this->getJson('/codes/gender');

        $response->assertStatus(200)
            ->assertExactJson([
                '0' => '不明',
                '1' => '男性',
                '2' => '女性',
            ]);
    }

    /** クエリパラメーターで絞り込める */
    public function test_index_filters_by_query_parameter(): void
    {
        $response = $this->getJson('/codes?code_type=color');

        $response->assertStatus(200)
            ->assertExactJson([
                '1' => '赤',
                '2' => '青',
            ]);
    }

    /** パスパラメーターとクエリパラメーターが同時に指定された場合はパスパラメーターが優先される */
    public function test_path_parameter_takes_precedence_over_query_parameter(): void
    {
        $response = $this->getJson('/codes/gender?code_type=color');

        $response->assertStatus(200)
            ->assertExactJson([
                '0' => '不明',
                '1' => '男性',
                '2' => '女性',
            ]);
    }

    /** 存在しない code_type を指定すると空の連想配列が返る */
    public function test_index_returns_empty_for_unknown_code_type(): void
    {
        $response = $this->getJson('/codes/unknown');

        $response->assertStatus(200)
            ->assertExactJson([]);
    }

    // -------------------------------------------------------------------------
    // show
    // -------------------------------------------------------------------------

    /** 1件取得: 存在するレコードが返る */
    public function test_show_returns_single_record(): void
    {
        $response = $this->getJson('/codes/gender/1');

        $response->assertStatus(200)
            ->assertJson([
                'code_type'  => 'gender',
                'code'       => 1,
                'name'       => '男性',
                'sort_order' => 1,
            ]);
    }

    /** 1件取得: 存在しないレコードは 404 */
    public function test_show_returns_404_for_unknown_record(): void
    {
        $this->getJson('/codes/gender/99')->assertStatus(404);
    }

    // -------------------------------------------------------------------------
    // store
    // -------------------------------------------------------------------------

    /** 新規登録: 正常に登録できる */
    public function test_store_creates_a_code(): void
    {
        $response = $this->postJson('/codes', [
            'code_type'  => 'test_type',
            'code'       => 1,
            'name'       => 'テスト',
            'sort_order' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'code_type' => 'test_type',
                'code'      => 1,
                'name'      => 'テスト',
            ]);
    }

    /** 新規登録: 必須項目が欠けていると 422 */
    public function test_store_requires_name(): void
    {
        $this->postJson('/codes', [
            'code_type' => 'test_type',
            'code'      => 1,
        ])->assertStatus(422)
          ->assertJsonValidationErrors(['name']);
    }

    /** 新規登録: code_type + code が重複すると 422 */
    public function test_store_fails_on_duplicate_composite_key(): void
    {
        $this->postJson('/codes', [
            'code_type' => 'gender',
            'code'      => 1,
            'name'      => '重複',
        ])->assertStatus(422)
          ->assertJsonPath('errors.code.0', 'この code_type と code の組み合わせは既に存在します');
    }

    // -------------------------------------------------------------------------
    // update
    // -------------------------------------------------------------------------

    /** 更新: name と sort_order を変更できる */
    public function test_update_modifies_name_and_sort_order(): void
    {
        $response = $this->putJson('/codes/gender/1', [
            'name'       => '男性（更新）',
            'sort_order' => 10,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'code_type'  => 'gender',
                'code'       => 1,
                'name'       => '男性（更新）',
                'sort_order' => 10,
            ]);
    }

    /** 更新: 存在しないレコードは 404 */
    public function test_update_returns_404_for_unknown_record(): void
    {
        $this->putJson('/codes/gender/99', [
            'name' => 'ゴースト',
        ])->assertStatus(404);
    }

    /** 更新: name が欠けていると 422 */
    public function test_update_requires_name(): void
    {
        $this->putJson('/codes/gender/1', [
            'sort_order' => 1,
        ])->assertStatus(422)
          ->assertJsonValidationErrors(['name']);
    }

    // -------------------------------------------------------------------------
    // destroy
    // -------------------------------------------------------------------------

    /** 削除: 正常に削除できる */
    public function test_destroy_deletes_a_code(): void
    {
        $this->deleteJson('/codes/gender/0')->assertStatus(204);

        $this->getJson('/codes/gender/0')->assertStatus(404);
    }

    /** 削除: 存在しないレコードは 404 */
    public function test_destroy_returns_404_for_unknown_record(): void
    {
        $this->deleteJson('/codes/gender/99')->assertStatus(404);
    }
}
