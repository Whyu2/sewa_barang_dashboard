export const getStatusSeverity = (s) => ({ rented: 'info', returned: 'success', overdue: 'danger', available: 'success' }[s] || 'secondary');
export const getStatusColor = (s) => ({ rented: '#60a5fa', returned: '#34d399', overdue: '#f87171', available: '#34d399' }[s] || '#9ca3af');
export const getStatusLabel = (s) => ({ rented: 'Disewa', returned: 'Dikembalikan', overdue: 'Pengembalian Telat', available: 'Tersedia' }[s] || s);
export default getStatusSeverity;
