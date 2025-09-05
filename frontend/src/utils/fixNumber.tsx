// Function to fix number to a certain decimal place

export const FixNumber = (num: number, fix = 2): string => {
    if (!num) return '0';

    const roundedNum = Math.round(Number(num) * Math.pow(10, fix)) / Math.pow(10, fix);

    if (roundedNum % 1 === 0) {
        return roundedNum.toFixed(0);
    } else {
        const formattedNum = roundedNum.toFixed(fix);
        const parts = formattedNum.split('.');

        if (parts.length === 1) {
            return parts[0];
        } else {
            return `${parts[0]}.${parts[1].padEnd(fix, '0')}`;
        }
    }
};