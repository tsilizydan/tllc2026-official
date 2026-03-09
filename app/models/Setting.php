<?php
/**
 * TSILIZY LLC — Setting Model
 */

namespace App\Models;

use Core\Model;
use Core\Database;

class Setting extends Model
{
    protected static string $table = 'settings';
    protected static array $fillable = [
        'key',
        'value',
        'type',
        'group'
    ];

    private static array $cache = [];

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $result = Database::query(
            "SELECT value, type FROM settings WHERE `key` = ? LIMIT 1",
            [$key]
        );

        if (empty($result)) {
            return $default;
        }

        $value = self::castValue($result[0]['value'], $result[0]['type']);
        self::$cache[$key] = $value;
        
        return $value;
    }

    /**
     * Set setting value
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general'): bool
    {
        $stringValue = is_array($value) || is_object($value) ? json_encode($value) : (string)$value;
        
        $existing = Database::query("SELECT id FROM settings WHERE `key` = ?", [$key]);
        
        if (!empty($existing)) {
            $result = Database::execute(
                "UPDATE settings SET value = ?, type = ?, `group` = ?, updated_at = NOW() WHERE `key` = ?",
                [$stringValue, $type, $group, $key]
            );
        } else {
            $result = Database::execute(
                "INSERT INTO settings (`key`, value, type, `group`, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())",
                [$key, $stringValue, $type, $group]
            );
        }

        if ($result) {
            self::$cache[$key] = self::castValue($stringValue, $type);
        }

        return $result;
    }

    /**
     * Get all settings by group
     */
    public static function getGroup(string $group): array
    {
        $results = Database::query(
            "SELECT * FROM settings WHERE `group` = ? ORDER BY `key`",
            [$group]
        );

        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key']] = self::castValue($row['value'], $row['type']);
        }

        return $settings;
    }

    /**
     * Get all settings
     */
    public static function getAll(): array
    {
        $results = Database::query("SELECT * FROM settings ORDER BY `group`, `key`");

        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key']] = self::castValue($row['value'], $row['type']);
        }

        return $settings;
    }

    /**
     * Cast value to appropriate type
     */
    private static function castValue($value, string $type)
    {
        return match($type) {
            'boolean', 'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'int' => (int)$value,
            'float', 'double' => (float)$value,
            'array', 'json' => json_decode($value, true) ?? [],
            default => $value
        };
    }

    /**
     * Clear cache
     */
    public static function clearCache(): void
    {
        self::$cache = [];
    }
}
