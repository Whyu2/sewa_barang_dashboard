export const formatDateID = (value) => {
    if (!value) return '-';
    const d = new Date(value);
    if (isNaN(d)) return value;
    return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).format(d);
};
export default formatDateID;
