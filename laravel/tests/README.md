# テスト一覧

## テスト実行方法

```bash
# 全テスト実行
docker compose exec -w /var/www/laravel php php artisan test

# ファイル指定
docker compose exec -w /var/www/laravel php php artisan test tests/Feature/Api/CodeControllerTest.php
docker compose exec -w /var/www/laravel php php artisan test tests/Feature/Api/OrganizationControllerTest.php
```

## 共通事項

- トレイト: `DatabaseTransactions` — 各テストをトランザクションでラップし終了時にロールバックする（シードデータを汚染しない）
- `setUp()` でテスト専用データを INSERT し、プロパティに ID を保持して各テストから参照する

---

## CodeControllerTest（15テスト）

**ファイル:** `tests/Feature/Api/CodeControllerTest.php`

**対象エンドポイント:** `GET|POST /codes`, `GET|PUT|DELETE /codes/{code_type}`, `GET|PUT|DELETE /codes/{code_type}/{code}`

**setUp:** `codes` テーブルに `code_type=gender`（0:不明, 1:男性, 2:女性）と `code_type=color`（1:赤, 2:青）を投入

### index（5テスト）

| テスト名 | 内容 |
|---|---|
| `test_index_returns_all_codes_as_map` | 全件取得で `{code: name}` 形式の連想配列が返る |
| `test_index_filters_by_path_parameter` | パスパラメーター `/codes/gender` で絞り込める |
| `test_index_filters_by_query_parameter` | クエリパラメーター `?code_type=color` で絞り込める |
| `test_path_parameter_takes_precedence_over_query_parameter` | パスとクエリが競合する場合パスが優先される |
| `test_index_returns_empty_for_unknown_code_type` | 存在しない `code_type` は空の連想配列（200）が返る |

### show（2テスト）

| テスト名 | 内容 |
|---|---|
| `test_show_returns_single_record` | 存在するレコードが全フィールドつきで返る |
| `test_show_returns_404_for_unknown_record` | 存在しないレコードは 404 |

### store（3テスト）

| テスト名 | 内容 |
|---|---|
| `test_store_creates_a_code` | 正常登録で 201 が返る |
| `test_store_requires_name` | `name` 欠落で 422（バリデーションエラー） |
| `test_store_fails_on_duplicate_composite_key` | `code_type + code` の重複で 422（独自エラーメッセージ付き） |

### update（3テスト）

| テスト名 | 内容 |
|---|---|
| `test_update_modifies_name_and_sort_order` | `name` と `sort_order` を更新できる |
| `test_update_returns_404_for_unknown_record` | 存在しないレコードは 404 |
| `test_update_requires_name` | `name` 欠落で 422 |

### destroy（2テスト）

| テスト名 | 内容 |
|---|---|
| `test_destroy_deletes_a_code` | 正常削除で 204、その後 GET すると 404 |
| `test_destroy_returns_404_for_unknown_record` | 存在しないレコードは 404 |

---

## OrganizationControllerTest（20テスト）

**ファイル:** `tests/Feature/Api/OrganizationControllerTest.php`

**対象エンドポイント:** `GET|POST /organizations`, `GET|PUT|DELETE /organizations/{id}`

**setUp:**
1. `persons` テーブルにテスト用人物（テスト 太郎）を INSERT → `$personId` に保存
2. `organizations` テーブルに親組織（`$orgId`）と子組織（`$childOrgId`, `parent_id=$orgId`）を INSERT

### index（4テスト）

| テスト名 | 内容 |
|---|---|
| `test_index_returns_200_with_array` | 200 かつ JSON 配列が返る |
| `test_index_is_ordered_by_id` | `id` 昇順で返る |
| `test_index_includes_representative_label` | `representative_label` フィールドに `姓+名` が含まれる |
| `test_index_representative_label_is_null_when_no_representative` | `representative_id` が NULL のレコードは `representative_label` が null |

### show（4テスト）

| テスト名 | 内容 |
|---|---|
| `test_show_returns_organization` | 指定 ID のレコードが返る |
| `test_show_includes_representative_label` | `representative_label` に `姓+名` が含まれる |
| `test_show_returns_child_organization` | `parent_id` を持つ子組織も取得できる |
| `test_show_returns_404_for_unknown` | 存在しない ID は 404 |

### store（5テスト）

| テスト名 | 内容 |
|---|---|
| `test_store_creates_organization` | `name` のみの最小リクエストで 201 が返る |
| `test_store_creates_organization_with_all_fields` | 全フィールド指定で正常登録できる |
| `test_store_requires_name` | `name` 欠落で 422 |
| `test_store_validates_parent_id_exists` | 存在しない `parent_id` で 422 |
| `test_store_validates_representative_id_exists` | 存在しない `representative_id` で 422 |

### update（4テスト）

| テスト名 | 内容 |
|---|---|
| `test_update_modifies_organization` | `name` と `status` を更新できる |
| `test_update_can_change_parent_id` | `parent_id` を別の組織に変更できる |
| `test_update_returns_404_for_unknown` | 存在しない ID は 404 |
| `test_update_requires_name` | `name` 欠落で 422 |

### destroy（3テスト）

| テスト名 | 内容 |
|---|---|
| `test_destroy_deletes_organization` | 子→親の順で削除でき、削除後は 404 になる |
| `test_destroy_returns_404_for_unknown` | 存在しない ID は 404 |
| `test_destroy_fails_when_has_children` | 子組織を持つ親を削除しようとすると 500（FK 制約違反） |

---

## テスト合計

| クラス | テスト数 | アサーション数 |
|---|---|---|
| CodeControllerTest | 15 | — |
| OrganizationControllerTest | 20 | 39 |
| **合計** | **35** | — |
