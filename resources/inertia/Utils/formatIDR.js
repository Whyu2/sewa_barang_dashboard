export const formatIDR = (value) => {
    if (value === null || value === undefined || value === '') return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(Number(value));
};
export default formatIDR;
