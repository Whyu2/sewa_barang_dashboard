export const toISODate = (d) => {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
};

export const addDaysISO = (dateStr, days) => {
    if (!dateStr || !days) return null;
    const parts = String(dateStr).substring(0, 10).split('-').map(Number);
    if (parts.length !== 3 || parts.some(isNaN)) return null;
    const d = new Date(parts[0], parts[1] - 1, parts[2]);
    d.setDate(d.getDate() + Number(days));
    return toISODate(d);
};

export const diffDays = (startStr, endStr) => {
    if (!startStr || !endStr) return null;
    const p1 = String(startStr).substring(0, 10).split('-').map(Number);
    const p2 = String(endStr).substring(0, 10).split('-').map(Number);
    if (p1.length !== 3 || p2.length !== 3 || [...p1, ...p2].some(isNaN)) return null;
    const a = new Date(p1[0], p1[1] - 1, p1[2]);
    const b = new Date(p2[0], p2[1] - 1, p2[2]);
    return Math.round((b - a) / 86400000);
};

export const formatDurationDays = (startStr, endStr) => {
    const n = diffDays(startStr, endStr);
    if (n === null) return '-';
    return `${n} hari`;
};

export default { addDaysISO, diffDays, formatDurationDays };
