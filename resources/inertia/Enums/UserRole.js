/**
 * Enum role pengguna — mirror dari App\Enums\UserRole di BE.
 * Satu-satunya tempat string 'admin'/'staff' ditulis di FE.
 */
export const USER_ROLES = Object.freeze({
    ADMIN: 'admin',
    STAFF: 'staff',
});

export const USER_ROLE_VALUES = Object.freeze(Object.values(USER_ROLES));

export const DEFAULT_USER_ROLE = USER_ROLES.STAFF;

export const USER_ROLE_OPTIONS = Object.freeze([
    { label: 'Admin', value: USER_ROLES.ADMIN },
    { label: 'Staf', value: USER_ROLES.STAFF },
]);

export const isUserRole = (value) => USER_ROLE_VALUES.includes(value);

export default USER_ROLES;
