<?php
/**
 * TSILIZY LLC — Base Model
 */

namespace Core;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    protected static bool $softDeletes = true;
    protected static bool $timestamps = true;

    /**
     * Get all records
     */
    public static function all(array $columns = ['*'], string $orderBy = 'created_at', string $direction = 'DESC'): array
    {
        $cols = implode(', ', $columns);
        $table = static::$table;
        
        $sql = "SELECT {$cols} FROM {$table}";
        
        if (static::$softDeletes) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        
        $sql .= " ORDER BY {$orderBy} {$direction}";

        return Database::query($sql);
    }

    /**
     * Find record by ID
     */
    public static function find(int $id): ?array
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        
        $sql = "SELECT * FROM {$table} WHERE {$pk} = ?";
        
        if (static::$softDeletes) {
            $sql .= " AND deleted_at IS NULL";
        }

        $result = Database::query($sql, [$id]);
        return $result[0] ?? null;
    }

    /**
     * Find record by column
     */
    public static function findBy(string $column, $value): ?array
    {
        $table = static::$table;
        
        $sql = "SELECT * FROM {$table} WHERE {$column} = ?";
        
        if (static::$softDeletes) {
            $sql .= " AND deleted_at IS NULL";
        }

        $result = Database::query($sql, [$value]);
        return $result[0] ?? null;
    }

    /**
     * Find multiple records by column
     */
    public static function where(string $column, $value, string $orderBy = 'created_at', string $direction = 'DESC'): array
    {
        $table = static::$table;
        
        $sql = "SELECT * FROM {$table} WHERE {$column} = ?";
        
        if (static::$softDeletes) {
            $sql .= " AND deleted_at IS NULL";
        }
        
        $sql .= " ORDER BY {$orderBy} {$direction}";

        return Database::query($sql, [$value]);
    }

    /**
     * Create a new record
     */
    public static function create(array $data): ?int
    {
        $table = static::$table;
        
        // Filter only fillable fields
        $data = array_intersect_key($data, array_flip(static::$fillable));
        
        // Add timestamps
        if (static::$timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        return Database::insert($sql, array_values($data));
    }

    /**
     * Update a record
     */
    public static function update(int $id, array $data): bool
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        
        // Filter only fillable fields
        $data = array_intersect_key($data, array_flip(static::$fillable));
        
        // Add updated timestamp
        if (static::$timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = ?";
        }
        $setString = implode(', ', $sets);
        
        $sql = "UPDATE {$table} SET {$setString} WHERE {$pk} = ?";
        
        $values = array_values($data);
        $values[] = $id;

        return Database::execute($sql, $values);
    }

    /**
     * Delete a record (soft delete if enabled)
     */
    public static function delete(int $id): bool
    {
        $table = static::$table;
        $pk = static::$primaryKey;

        if (static::$softDeletes) {
            $sql = "UPDATE {$table} SET deleted_at = ? WHERE {$pk} = ?";
            return Database::execute($sql, [date('Y-m-d H:i:s'), $id]);
        }

        $sql = "DELETE FROM {$table} WHERE {$pk} = ?";
        return Database::execute($sql, [$id]);
    }

    /**
     * Permanently delete a record
     */
    public static function forceDelete(int $id): bool
    {
        $table = static::$table;
        $pk = static::$primaryKey;
        
        $sql = "DELETE FROM {$table} WHERE {$pk} = ?";
        return Database::execute($sql, [$id]);
    }

    /**
     * Restore a soft-deleted record
     */
    public static function restore(int $id): bool
    {
        if (!static::$softDeletes) {
            return false;
        }

        $table = static::$table;
        $pk = static::$primaryKey;
        
        $sql = "UPDATE {$table} SET deleted_at = NULL, updated_at = ? WHERE {$pk} = ?";
        return Database::execute($sql, [date('Y-m-d H:i:s'), $id]);
    }

    /**
     * Count records
     */
    public static function count(?string $condition = null, array $params = []): int
    {
        $table = static::$table;
        
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        
        $conditions = [];
        if (static::$softDeletes) {
            $conditions[] = "deleted_at IS NULL";
        }
        if ($condition) {
            $conditions[] = $condition;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $result = Database::query($sql, $params);
        return (int)($result[0]['count'] ?? 0);
    }

    /**
     * Paginate records
     */
    public static function paginate(int $page = 1, int $perPage = ITEMS_PER_PAGE, ?string $condition = null, array $params = [], string $orderBy = 'created_at', string $direction = 'DESC'): array
    {
        $table = static::$table;
        $offset = ($page - 1) * $perPage;

        // Build WHERE clause
        $conditions = [];
        if (static::$softDeletes) {
            $conditions[] = "deleted_at IS NULL";
        }
        if ($condition) {
            $conditions[] = $condition;
        }
        
        $whereClause = !empty($conditions) ? " WHERE " . implode(' AND ', $conditions) : '';

        // Get total count
        $countSql = "SELECT COUNT(*) as count FROM {$table}" . $whereClause;
        $countResult = Database::query($countSql, $params);
        $total = (int)($countResult[0]['count'] ?? 0);

        // Get records
        $sql = "SELECT * FROM {$table}" . $whereClause . " ORDER BY {$orderBy} {$direction} LIMIT {$perPage} OFFSET {$offset}";
        $records = Database::query($sql, $params);

        return [
            'data' => $records,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => (int)ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }

    /**
     * Search records
     */
    public static function search(string $query, array $columns, int $page = 1, int $perPage = ITEMS_PER_PAGE): array
    {
        $table = static::$table;
        $offset = ($page - 1) * $perPage;
        
        // Build search conditions
        $searchConditions = [];
        $params = [];
        foreach ($columns as $col) {
            $searchConditions[] = "{$col} LIKE ?";
            $params[] = "%{$query}%";
        }
        $searchClause = "(" . implode(' OR ', $searchConditions) . ")";

        // Build WHERE clause
        $conditions = [$searchClause];
        if (static::$softDeletes) {
            $conditions[] = "deleted_at IS NULL";
        }
        $whereClause = " WHERE " . implode(' AND ', $conditions);

        // Get total count
        $countSql = "SELECT COUNT(*) as count FROM {$table}" . $whereClause;
        $countResult = Database::query($countSql, $params);
        $total = (int)($countResult[0]['count'] ?? 0);

        // Get records
        $sql = "SELECT * FROM {$table}" . $whereClause . " ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}";
        $records = Database::query($sql, $params);

        return [
            'data' => $records,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => (int)ceil($total / $perPage),
            'query' => $query
        ];
    }

    /**
     * Get records with raw SQL
     */
    public static function raw(string $sql, array $params = []): array
    {
        return Database::query($sql, $params);
    }

    /**
     * Execute raw SQL
     */
    public static function rawExecute(string $sql, array $params = []): bool
    {
        return Database::execute($sql, $params);
    }
}
