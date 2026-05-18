<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use DatabaseTransactions;

    private int $personId;
    private int $orgId;
    private int $childOrgId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->personId = DB::table('persons')->insertGetId([
            'last_name'       => 'テスト',
            'first_name'      => '太郎',
            'last_name_kana'  => 'テスト',
            'first_name_kana' => 'タロウ',
            'email'           => 'org.test.' . uniqid() . '@example.com',
            'phone'           => '03-0000-0001',
            'birth_date'      => '1990-01-01',
            'gender'          => 1,
            'zipcode'         => '1000001',
            'prefecture_code' => 13,
            'city'            => '千代田区',
            'address_line'    => 'テスト1-1-1',
            'created_at'      => now()->toDateTimeString(),
            'updated_at'      => now()->toDateTimeString(),
        ]);

        $this->orgId = DB::table('organizations')->insertGetId([
            'name'              => 'テスト組織',
            'name_kana'         => 'テストソシキ',
            'email'             => 'test-org@example.com',
            'phone'             => '03-0000-0001',
            'zipcode'           => '1000001',
            'prefecture_code'   => 13,
            'city'              => '千代田区',
            'address_line'      => 'テスト1-1-1',
            'representative_id' => $this->personId,
            'established_date'  => '2000-01-01',
            'status'            => 1,
            'created_at'        => now()->toDateTimeString(),
            'updated_at'        => now()->toDateTimeString(),
        ]);

        $this->childOrgId = DB::table('organizations')->insertGetId([
            'parent_id'         => $this->orgId,
            'name'              => '子テスト組織',
            'name_kana'         => 'コテストソシキ',
            'email'             => 'child-org@example.com',
            'phone'             => '03-0000-0002',
            'zipcode'           => '1000001',
            'prefecture_code'   => 13,
            'city'              => '千代田区',
            'address_line'      => 'テスト1-1-2',
            'representative_id' => $this->personId,
            'established_date'  => '2010-01-01',
            'status'            => 1,
            'created_at'        => now()->toDateTimeString(),
            'updated_at'        => now()->toDateTimeString(),
        ]);
    }

    // -------------------------------------------------------------------------
    // index
    // -------------------------------------------------------------------------

    /** 全件取得: 200 が返り配列が返る */
    public function test_index_returns_200_with_array(): void
    {
        $this->getJson('/organizations')
            ->assertStatus(200)
            ->assertJsonIsArray();
    }

    /** 全件取得: id 昇順で返る */
    public function test_index_is_ordered_by_id(): void
    {
        $response = $this->getJson('/organizations');
        $ids = collect($response->json())->pluck('id')->values()->all();

        $this->assertSame($ids, collect($ids)->sort()->values()->all());
    }

    /** 全件取得: representative_label が含まれる */
    public function test_index_includes_representative_label(): void
    {
        $response = $this->getJson('/organizations');
        $record   = collect($response->json())->firstWhere('id', $this->orgId);

        $this->assertArrayHasKey('representative_label', $record);
        $this->assertSame('テスト太郎', $record['representative_label']);
    }

    /** 全件取得: representative_id が NULL のレコードは representative_label が null */
    public function test_index_representative_label_is_null_when_no_representative(): void
    {
        $noRepId = DB::table('organizations')->insertGetId([
            'name'       => '代表者なし組織',
            'status'     => 1,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ]);

        $response = $this->getJson('/organizations');
        $record   = collect($response->json())->firstWhere('id', $noRepId);

        $this->assertNull($record['representative_label']);
    }

    // -------------------------------------------------------------------------
    // show
    // -------------------------------------------------------------------------

    /** 1件取得: 正常に返る */
    public function test_show_returns_organization(): void
    {
        $this->getJson("/organizations/{$this->orgId}")
            ->assertStatus(200)
            ->assertJson([
                'id'   => $this->orgId,
                'name' => 'テスト組織',
            ]);
    }

    /** 1件取得: representative_label が含まれる */
    public function test_show_includes_representative_label(): void
    {
        $this->getJson("/organizations/{$this->orgId}")
            ->assertStatus(200)
            ->assertJsonPath('representative_label', 'テスト太郎');
    }

    /** 1件取得: parent_id を持つ子組織も取得できる */
    public function test_show_returns_child_organization(): void
    {
        $this->getJson("/organizations/{$this->childOrgId}")
            ->assertStatus(200)
            ->assertJson([
                'id'        => $this->childOrgId,
                'parent_id' => $this->orgId,
            ]);
    }

    /** 1件取得: 存在しないレコードは 404 */
    public function test_show_returns_404_for_unknown(): void
    {
        $this->getJson('/organizations/99999999')->assertStatus(404);
    }

    // -------------------------------------------------------------------------
    // store
    // -------------------------------------------------------------------------

    /** 新規登録: name のみで登録できる */
    public function test_store_creates_organization(): void
    {
        $this->postJson('/organizations', ['name' => '新規組織'])
            ->assertStatus(201)
            ->assertJson(['name' => '新規組織']);
    }

    /** 新規登録: 全フィールドを指定して登録できる */
    public function test_store_creates_organization_with_all_fields(): void
    {
        $response = $this->postJson('/organizations', [
            'name'              => '全項目組織',
            'name_kana'         => 'ゼンコウモクソシキ',
            'email'             => 'full@example.com',
            'phone'             => '03-1111-2222',
            'zipcode'           => '1000001',
            'prefecture_code'   => 13,
            'city'              => '千代田区',
            'address_line'      => '丸の内1-1-1',
            'parent_id'         => $this->orgId,
            'representative_id' => $this->personId,
            'established_date'  => '2020-04-01',
            'status'            => 1,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'name'              => '全項目組織',
                'parent_id'         => $this->orgId,
                'representative_id' => $this->personId,
                'status'            => 1,
            ]);
    }

    /** 新規登録: name が欠けていると 422 */
    public function test_store_requires_name(): void
    {
        $this->postJson('/organizations', ['status' => 1])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** 新規登録: 存在しない parent_id は 422 */
    public function test_store_validates_parent_id_exists(): void
    {
        $this->postJson('/organizations', [
            'name'      => '新規組織',
            'parent_id' => 99999999,
        ])->assertStatus(422)
          ->assertJsonValidationErrors(['parent_id']);
    }

    /** 新規登録: 存在しない representative_id は 422 */
    public function test_store_validates_representative_id_exists(): void
    {
        $this->postJson('/organizations', [
            'name'              => '新規組織',
            'representative_id' => 99999999,
        ])->assertStatus(422)
          ->assertJsonValidationErrors(['representative_id']);
    }

    // -------------------------------------------------------------------------
    // update
    // -------------------------------------------------------------------------

    /** 更新: name と status を変更できる */
    public function test_update_modifies_organization(): void
    {
        $this->putJson("/organizations/{$this->orgId}", [
            'name'   => '更新済み組織',
            'status' => 0,
        ])->assertStatus(200)
          ->assertJson([
              'id'     => $this->orgId,
              'name'   => '更新済み組織',
              'status' => 0,
          ]);
    }

    /** 更新: parent_id を変更できる */
    public function test_update_can_change_parent_id(): void
    {
        // childOrgId は既に orgId を親に持つので、それを別の新しい親に変える
        $newParentId = DB::table('organizations')->insertGetId([
            'name'       => '新しい親組織',
            'status'     => 1,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ]);

        $this->putJson("/organizations/{$this->childOrgId}", [
            'name'      => '子テスト組織',
            'parent_id' => $newParentId,
        ])->assertStatus(200)
          ->assertJsonPath('parent_id', $newParentId);
    }

    /** 更新: 存在しないレコードは 404 */
    public function test_update_returns_404_for_unknown(): void
    {
        $this->putJson('/organizations/99999999', ['name' => '存在しない'])
            ->assertStatus(404);
    }

    /** 更新: name が欠けていると 422 */
    public function test_update_requires_name(): void
    {
        $this->putJson("/organizations/{$this->orgId}", ['status' => 1])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    // -------------------------------------------------------------------------
    // destroy
    // -------------------------------------------------------------------------

    /** 削除: 正常に削除できる（子→親の順で削除） */
    public function test_destroy_deletes_organization(): void
    {
        $this->deleteJson("/organizations/{$this->childOrgId}")->assertStatus(204);
        $this->deleteJson("/organizations/{$this->orgId}")->assertStatus(204);

        $this->getJson("/organizations/{$this->orgId}")->assertStatus(404);
    }

    /** 削除: 存在しないレコードは 404 */
    public function test_destroy_returns_404_for_unknown(): void
    {
        $this->deleteJson('/organizations/99999999')->assertStatus(404);
    }

    /** 削除: 子組織を持つ組織を削除しようとすると 500（FK制約） */
    public function test_destroy_fails_when_has_children(): void
    {
        // childOrgId が orgId を参照しているので orgId を直接削除するとFK違反
        $this->deleteJson("/organizations/{$this->orgId}")->assertStatus(500);
    }
}
