export const formatTRX = (id) => `TRX-${String(id ?? 0).padStart(4, '0')}`;
export default formatTRX;
