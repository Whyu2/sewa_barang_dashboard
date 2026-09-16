export const isLate = (returnDate, expectedDate) => {
  if (!returnDate || !expectedDate) return false;
  return new Date(returnDate).getTime() > new Date(expectedDate).getTime() + 3600000;
};
export const getDerivedStatus = (tx) => {
  if (tx?.status === 'returned' && isLate(tx.return_date, tx.expected_return_date)) return 'overdue';
  return tx?.status;
};
export const lateHours = (returnDate, expectedDate) => {
  if (!isLate(returnDate, expectedDate)) return 0;
  return Math.floor((new Date(returnDate).getTime() - new Date(expectedDate).getTime()) / 3600000);
};
