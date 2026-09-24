/**
 * Parse error hasil interceptor useAxios menjadi bentuk siap tampil.
 * BE mengirim { message: "Validasi gagal", errors: { field: [msg] } } (422).
 * Sebelum interceptor diperbaiki, `errors` hilang karena dibungkus new Error(message).
 * Sekarang interceptor melampirkan `err.data`, helper ini flatten ke detail list.
 */
export const parseApiError = (error, fallback = 'Request failed') => {
    const data = error?.data ?? error?.original?.response?.data ?? error?.response?.data;
    const message = data?.message || error?.message || fallback;
    const rawErrors = data?.errors;

    const fieldErrors = {};
    const details = [];
    if (rawErrors && typeof rawErrors === 'object') {
        Object.entries(rawErrors).forEach(([field, msgs]) => {
            const list = Array.isArray(msgs) ? msgs : [msgs];
            fieldErrors[field] = list[0];
            list.forEach((m) => details.push(String(m)));
        });
    }

    return {
        message,
        details,
        fieldErrors,
        // Satu baris gabungan untuk toast detail: "Validasi gagal: email sudah dipakai, ..."
        detailText: details.length ? details.join(', ') : message,
    };
};

export default parseApiError;
