<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Staff = 'staff';

    /**
     * Daftar nilai valid untuk aturan validasi `in:`.
     *
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $role) => $role->value, self::cases());
    }

    public static function default(): self
    {
        return self::Staff;
    }
}
